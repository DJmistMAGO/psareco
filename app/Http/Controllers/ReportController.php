<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Language;

class ReportController extends Controller
{
    public function index()
    {
        $monthStart = now()->startOfMonth();
        $monthEnd   = now()->endOfMonth();

        $stats = [
            'monthly_revenue' => Booking::where('status', 'completed')
                ->whereBetween('start_date', [$monthStart, $monthEnd])
                ->sum('total_amount'),
            'monthly_bookings' => Booking::where('status', 'completed')
                ->whereBetween('start_date', [$monthStart, $monthEnd])
                ->count(),
            'low_stock_count' => Inventory::whereColumn('quantity', '<=', 'reorder_level')->count(),
            'inventory_value' => Inventory::sum(DB::raw('quantity * price')),
        ];

    return view('admin.reports', compact('stats'));
    }

    public function preview(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'types'      => ['required', 'array', 'min:1'],
            'types.*'    => ['in:financial,machinery'],
        ]);

        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end   = Carbon::parse($validated['end_date'])->endOfDay();
        $types = $validated['types'];

        $response = [];

        if (in_array('financial', $types, true)) {
            $bookings = Booking::with(['machine', 'user'])
                ->where('status', 'completed')
                ->whereBetween('start_date', [$start, $end])
                ->orderBy('start_date')
                ->get();

            $response['bookings'] = $bookings->map(fn ($b) => [
                'machine'      => $b->machine->name ?? 'N/A',
                'customer'     => $b->user->name ?? 'N/A',
                'start_date'   => $b->start_date?->format('M d, Y'),
                'end_date'     => $b->end_date?->format('M d, Y'),
                'days'         => $b->days,
                'total_hours'  => number_format((float) $b->total_hours, 2),
                'total_amount' => number_format((float) $b->total_amount, 2),
            ]);
            $response['bookings_total'] = number_format($bookings->sum('total_amount'), 2);
        }

        if (in_array('machinery', $types, true)) {
            $inventory = Inventory::whereBetween('created_at', [$start, $end])
                ->orderBy('name')
                ->get();

            $response['inventory'] = $inventory->map(fn ($i) => [
                'name'           => $i->name,
                'type'           => $i->type,
                'quantity'       => number_format((float) $i->quantity, 2),
                'unit'           => $i->unit,
                'price'          => number_format((float) $i->price, 2),
                'reorder_level'  => number_format((float) $i->reorder_level, 2),
                'expiration'     => $i->expiration_date?->format('M d, Y'),
                'low_stock'      => $i->quantity <= $i->reorder_level,
            ]);
        }

        return response()->json($response);
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'types'      => ['required', 'array', 'min:1'],
            'types.*'    => ['in:financial,machinery'],
        ]);

        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end   = Carbon::parse($validated['end_date'])->endOfDay();
        $types = $validated['types'];

        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::EN_US));
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(10);

        if (in_array('financial', $types, true)) {
            $bookings = Booking::with(['machine', 'user'])
                ->where('status', 'completed')
                ->whereBetween('start_date', [$start, $end])
                ->orderBy('start_date')
                ->get();

            $this->addFinancialSection($phpWord, $bookings, $start, $end);
        }

        if (in_array('machinery', $types, true)) {
            $inventory = Inventory::whereBetween('created_at', [$start, $end])
                ->orderBy('name')
                ->get();

            $this->addMachinerySection($phpWord, $inventory, $start, $end);
        }

        $filename = 'psareco-report-' . now()->format('Y-m-d_His') . '.docx';
        $tempPath = storage_path('app/' . $filename);

        IOFactory::createWriter($phpWord, 'Word2007')->save($tempPath);

        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }

    private function addFinancialSection(PhpWord $phpWord, $bookings, Carbon $start, Carbon $end): void
    {
        $section = $phpWord->addSection();

        $section->addText('PSARECO Enterprise Report', ['bold' => true, 'size' => 18, 'color' => '1E293B']);
        $section->addText('Financial / Sales Report', ['bold' => true, 'size' => 14, 'color' => '059669']);
        $section->addText(
            'Period: ' . $start->format('M d, Y') . ' - ' . $end->format('M d, Y'),
            ['size' => 9, 'color' => '64748B']
        );
        $section->addTextBreak(1);

        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => 'E2E8F0',
            'cellMargin' => 80,
        ];
        $headerCellStyle = ['bgColor' => '059669', 'valign' => 'center'];
        $headerFontStyle = ['bold' => true, 'color' => 'FFFFFF', 'size' => 9];
        $cellFontStyle = ['size' => 9];

        $table = $section->addTable($tableStyle);

        $table->addRow(400);
        foreach (['Machine', 'Customer', 'Start Date', 'End Date', 'Days', 'Hours', 'Amount (₱)'] as $header) {
            $table->addCell(1600, $headerCellStyle)->addText($header, $headerFontStyle);
        }

        $grandTotal = 0;

        foreach ($bookings as $booking) {
            $table->addRow();
            $table->addCell(1600)->addText($booking->machine->name ?? 'N/A', $cellFontStyle);
            $table->addCell(1600)->addText($booking->user->name ?? 'N/A', $cellFontStyle);
            $table->addCell(1600)->addText($booking->start_date?->format('M d, Y') ?? '-', $cellFontStyle);
            $table->addCell(1600)->addText($booking->end_date?->format('M d, Y') ?? '-', $cellFontStyle);
            $table->addCell(1600)->addText((string) $booking->days, $cellFontStyle);
            $table->addCell(1600)->addText(number_format((float) $booking->total_hours, 2), $cellFontStyle);
            $table->addCell(1600)->addText(number_format((float) $booking->total_amount, 2), $cellFontStyle);

            $grandTotal += (float) $booking->total_amount;
        }

        if ($bookings->isEmpty()) {
            $table->addRow();
            $cell = $table->addCell(11200);
            $cell->getStyle()->setGridSpan(7);
            $cell->addText('No completed bookings found for this period.', ['italic' => true, 'size' => 9, 'color' => '64748B']);
        } else {
            $table->addRow();
            $totalLabelCell = $table->addCell(9600, ['bgColor' => 'F1F5F9']);
            $totalLabelCell->getStyle()->setGridSpan(6);
            $totalLabelCell->addText('Total Revenue', ['bold' => true, 'size' => 9]);
            $table->addCell(1600, ['bgColor' => 'F1F5F9'])->addText(number_format($grandTotal, 2), ['bold' => true, 'size' => 9]);
        }

        $section->addTextBreak(2);
    }

    private function addMachinerySection(PhpWord $phpWord, $inventory, Carbon $start, Carbon $end): void
    {
        $section = $phpWord->addSection();

        $section->addText('Machinery / Inventory Report', ['bold' => true, 'size' => 14, 'color' => '059669']);
        $section->addText(
            'Items added between ' . $start->format('M d, Y') . ' - ' . $end->format('M d, Y'),
            ['size' => 9, 'color' => '64748B']
        );
        $section->addTextBreak(1);

        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => 'E2E8F0',
            'cellMargin' => 80,
        ];
        $headerCellStyle = ['bgColor' => '059669', 'valign' => 'center'];
        $headerFontStyle = ['bold' => true, 'color' => 'FFFFFF', 'size' => 9];
        $cellFontStyle = ['size' => 9];
        $lowStockFontStyle = ['size' => 9, 'color' => 'DC2626', 'bold' => true];

        $table = $section->addTable($tableStyle);

        $table->addRow(400);
        foreach (['Name', 'Type', 'Quantity', 'Unit', 'Price (₱)', 'Reorder Level', 'Expiration'] as $header) {
            $table->addCell(1600, $headerCellStyle)->addText($header, $headerFontStyle);
        }

        foreach ($inventory as $item) {
            $isLowStock = $item->quantity <= $item->reorder_level;

            $table->addRow();
            $table->addCell(1600)->addText($item->name, $cellFontStyle);
            $table->addCell(1600)->addText($item->type ?? '-', $cellFontStyle);
            $table->addCell(1600)->addText(
                number_format((float) $item->quantity, 2),
                $isLowStock ? $lowStockFontStyle : $cellFontStyle
            );
            $table->addCell(1600)->addText($item->unit ?? '-', $cellFontStyle);
            $table->addCell(1600)->addText(number_format((float) $item->price, 2), $cellFontStyle);
            $table->addCell(1600)->addText(number_format((float) $item->reorder_level, 2), $cellFontStyle);
            $table->addCell(1600)->addText($item->expiration_date?->format('M d, Y') ?? '-', $cellFontStyle);
        }

        if ($inventory->isEmpty()) {
            $table->addRow();
            $cell = $table->addCell(11200);
            $cell->getStyle()->setGridSpan(7);
            $cell->addText('No inventory items found for this period.', ['italic' => true, 'size' => 9, 'color' => '64748B']);
        }

        $section->addTextBreak(1);
        $section->addText('Items in red indicate quantity at or below reorder level.', ['italic' => true, 'size' => 8, 'color' => '64748B']);
    }
}
