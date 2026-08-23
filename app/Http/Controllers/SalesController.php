<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpWord\TemplateProcessor;

class SalesController extends Controller
{

    public function index(Request $request)
    {
        $products = Inventory::query()
            ->where('quantity', '>', 0)
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'quantity', 'unit', 'expiration_date']);

        return view('admin.sales', [
            'products' => $products,
            'salesHistory' => $this->groupedHistory($request->string('search')->trim()->toString()),
            'search' => $request->string('search')->trim()->toString(),
        ]);
    }


    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'buyer_name'          => ['required', 'string', 'max:255'],
            'items'                => ['required', 'array', 'min:1'],
            'items.*.product_id'   => ['required', 'integer', 'exists:inventories,id'],
            'items.*.quantity'     => ['required', 'integer', 'min:1'],
        ]);

        try {
            $sale = DB::transaction(function () use ($validated) {
                $saleDate = Carbon::now();
                $rows = [];
                $grandTotal = 0;

                foreach ($validated['items'] as $item) {
                    $product = Inventory::where('id', $item['product_id'])->lockForUpdate()->firstOrFail();

                    if ($product->quantity < $item['quantity']) {
                        throw ValidationException::withMessages([
                            'items' => "Insufficient stock for {$product->name}. Only {$product->quantity} {$product->unit} left.",
                        ]);
                    }

                    $lineTotal = $product->price * $item['quantity'];
                    $grandTotal += $lineTotal;

                    $rows[] = Sales::create([
                        'product_id' => $product->id,
                        'quantity'   => $item['quantity'],
                        'price'      => $product->price,
                        'total'      => $lineTotal,
                        'buyer_name' => $validated['buyer_name'],
                        'sale_date'  => $saleDate,
                    ]);

                    $product->decrement('quantity', $item['quantity']);
                }

                return [
                    'buyer_name' => $validated['buyer_name'],
                    'sale_date'  => $saleDate->toDateTimeString(),
                    'total'      => $grandTotal,
                    'items'      => $rows,
                ];
            });
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'message' => 'Sale recorded successfully.',
            'sale'    => $sale,
        ]);
    }


    public function export()
    {
        $templatePath = storage_path('app/template/psareco-sales-template.docx');

        $templateProcessor = new TemplateProcessor($templatePath);

        $sales = Sales::with('product')->orderByDesc('sale_date')->get();

        $templateProcessor->setValue('generated_at', now()->format('M d, Y g:ia'));
        $templateProcessor->setValue('total_records', $sales->count());
        $templateProcessor->setValue('grand_total', '₱' . number_format($sales->sum('total'), 2));

        if ($sales->isEmpty()) {
            $templateProcessor->setValue('date#1', 'No sales recorded');
            foreach (['product', 'quantity', 'unit_price', 'total', 'buyer'] as $field) {
                $templateProcessor->setValue("{$field}#1", '—');
            }
        } else {
            $templateProcessor->cloneRow('date', $sales->count());

            foreach ($sales as $index => $sale) {
                $row = $index + 1;

                $templateProcessor->setValue("date#{$row}", optional($sale->sale_date)->format('M. d, Y g:ia'));
                $templateProcessor->setValue("product#{$row}", $sale->product->name ?? '—');
                $templateProcessor->setValue("qty#{$row}", $sale->quantity);
                $templateProcessor->setValue("unit_price#{$row}", '₱' . number_format($sale->price, 2));
                $templateProcessor->setValue("total#{$row}", '₱' . number_format($sale->total, 2));
                $templateProcessor->setValue("buyer#{$row}", $sale->buyer_name);
            }
        }

        $filename = 'sales-history-' . now()->format('Y-m-d-His') . '.docx';
        $tempPath = tempnam(sys_get_temp_dir(), 'sales_export') . '.docx';
        $templateProcessor->saveAs($tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }


    private function groupedHistory(string $search = '', int $perPage = 5)
    {
        $grouped = Sales::with('product')
            ->orderByDesc('sale_date')
            ->get()
            ->groupBy(fn ($sale) => $sale->buyer_name . '|' . $sale->sale_date)
            ->map(function ($rows) {
                $first = $rows->first();

                return [
                    'sale_date'  => $first->sale_date,
                    'buyer_name' => $first->buyer_name,
                    'total'      => $rows->sum('total'),
                    'items'      => $rows->map(fn ($r) => [
                        'name'     => $r->product->name ?? '—',
                        'quantity' => $r->quantity,
                        'unit'     => $r->product->unit ?? '',
                    ])->values(),
                ];
            })
            ->values();

        if ($search !== '') {
            $needle = mb_strtolower($search);

            $grouped = $grouped->filter(function ($sale) use ($needle) {
                if (str_contains(mb_strtolower($sale['buyer_name']), $needle)) {
                    return true;
                }

                return $sale['items']->contains(
                    fn ($item) => str_contains(mb_strtolower($item['name']), $needle)
                );
            })->values();
        }

        $page = Paginator::resolveCurrentPage('page');

        return new LengthAwarePaginator(
            $grouped->forPage($page, $perPage)->values(),
            $grouped->count(),
            $perPage,
            $page,
            [
                'path'     => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
                'query'    => $search !== '' ? ['search' => $search] : [],
            ]
        );
    }
}
