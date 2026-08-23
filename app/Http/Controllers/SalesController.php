<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SalesController extends Controller
{

    public function index()
    {
        $products = Inventory::query()
            ->where('quantity', '>', 0)
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'quantity', 'unit', 'expiration_date']);

        return view('admin.sales', [
            'products' => $products,
            'salesHistory' => $this->groupedHistory(),
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
                    // Lock the row so two simultaneous checkouts can't oversell the same stock.
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
        $filename = 'sales-history-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Product', 'Quantity', 'Unit Price', 'Total', 'Buyer']);

            Sales::with('product')->orderByDesc('sale_date')->chunk(200, function ($chunk) use ($handle) {
                foreach ($chunk as $sale) {
                    fputcsv($handle, [
                        optional($sale->sale_date)->format('Y-m-d H:i'),
                        $sale->product->name ?? '—',
                        $sale->quantity,
                        $sale->price,
                        $sale->total,
                        $sale->buyer_name,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }


    private function groupedHistory()
    {
        return Sales::with('product')
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
    }
}
