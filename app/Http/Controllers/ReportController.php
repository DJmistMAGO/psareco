<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Inventory;
use App\Models\Machinery;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Language;

class ReportController extends Controller
{

    private const DARK_GREEN   = '538135'; // headings, totals, thick divider
    private const BORDER_GREEN = 'A8D08D'; // table borders, thin divider
    private const FILL_GREEN   = 'E2EFD9'; // header row / total row fill
    private const LABEL_GRAY   = '808080'; // "Date Generated:" style labels
    private const LOW_STOCK_RED = 'DC2626';
    private const FONT_FAMILY  = 'Arial';

    private function logoPath(): ?string
    {
        $original = public_path('assets/images/PSARECO_logo.png');

        if (!file_exists($original)) {
            return null;
        }

        $cropped = storage_path('app/public/PSARECO_logo_square.png');

        if (!file_exists($cropped) || filemtime($cropped) < filemtime($original)) {
            $this->cropToSquare($original, $cropped);
        }

        return file_exists($cropped) ? $cropped : $original;
    }

    private function cropToSquare(string $sourcePath, string $destPath): void
    {
        $info = @getimagesize($sourcePath);
        if (!$info) {
            return;
        }

        [$width, $height] = $info;
        $size = min($width, $height);
        $srcX = (int) (($width - $size) / 2);
        $srcY = (int) (($height - $size) / 2);

        $source = match ($info[2]) {
            IMAGETYPE_PNG  => imagecreatefrompng($sourcePath),
            IMAGETYPE_JPEG => imagecreatefromjpeg($sourcePath),
            IMAGETYPE_GIF  => imagecreatefromgif($sourcePath),
            default        => null,
        };

        if (!$source) {
            return;
        }

        $square = imagecreatetruecolor($size, $size);
        imagealphablending($square, false);
        imagesavealpha($square, true);
        $transparent = imagecolorallocatealpha($square, 0, 0, 0, 127);
        imagefill($square, 0, 0, $transparent);

        imagecopy($square, $source, 0, 0, $srcX, $srcY, $size, $size);

        imagepng($square, $destPath);

        imagedestroy($source);
        imagedestroy($square);
    }

    public function index()
    {
        $monthStart = now()->startOfMonth();
        $monthEnd   = now()->endOfMonth();

        $stats = [
            'monthly_sales_income' => Sales::whereBetween('sale_date', [$monthStart, $monthEnd])->sum('total'),
            'monthly_booking_income' => Booking::where('status', 'completed')
                ->whereBetween('start_date', [$monthStart, $monthEnd])
                ->sum('total_amount'),
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
            'types.*'    => ['in:machinery,bookings,sales,inventory'],
        ]);

        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end   = Carbon::parse($validated['end_date'])->endOfDay();
        $types = $validated['types'];

        $response = [];

        if (in_array('machinery', $types, true)) {
            $machinery = Machinery::orderBy('machinery_name')->get();

            $response['machinery'] = $machinery->map(fn ($m) => [
                'machinery_name' => $m->machinery_name,
                'model'          => $m->model,
                'serial_number'  => $m->serial_number,
                'price'          => number_format((float) $m->price, 2),
                'status'         => $m->status,
            ]);
        }

        if (in_array('bookings', $types, true)) {
            $bookings = Booking::with(['machine', 'user'])
                ->where('status', 'completed')
                ->whereBetween('start_date', [$start, $end])
                ->orderBy('start_date')
                ->get();

            $response['bookings'] = $bookings->map(fn ($b) => [
                'machinery_name' => $b->machine->machinery_name ?? 'N/A',
                'customer'       => $b->user->name ?? 'N/A',
                'start_date'     => $b->start_date?->format('M d, Y'),
                'end_date'       => $b->end_date?->format('M d, Y'),
                'days'           => $b->days,
                'total_hours'    => number_format((float) $b->total_hours, 2),
                'total_amount'   => number_format((float) $b->total_amount, 2),
            ]);
            $response['bookings_total'] = number_format($bookings->sum('total_amount'), 2);
        }

        if (in_array('sales', $types, true)) {
            $sales = Sales::with('product')
                ->whereBetween('sale_date', [$start, $end])
                ->orderBy('sale_date')
                ->get();

            $response['sales'] = $sales->map(fn ($s) => [
                'sale_date'    => $s->sale_date?->format('M d, Y'),
                'product_name' => $s->product->name ?? 'N/A',
                'buyer_name'   => $s->buyer_name,
                'quantity'     => (int) $s->quantity,
                'price'        => number_format((float) $s->price, 2),
                'total'        => number_format((float) $s->total, 2),
            ]);
            $response['sales_total'] = number_format($sales->sum('total'), 2);
        }

        if (in_array('inventory', $types, true)) {
            $inventory = Inventory::orderBy('name')->get();

            $response['inventory'] = $inventory->map(fn ($i) => [
                'name'             => $i->name,
                'type'             => $i->type,
                'quantity'         => number_format((float) $i->quantity, 2),
                'unit'             => $i->unit,
                'price'            => number_format((float) $i->price, 2),
                'inventory_value'  => number_format((float) $i->quantity * (float) $i->price, 2),
                'reorder_level'    => number_format((float) $i->reorder_level, 2),
                'expiration'       => $i->expiration_date?->format('M d, Y'),
                'low_stock'        => $i->quantity <= $i->reorder_level,
            ]);
            $response['inventory_value'] = number_format(
                $inventory->sum(fn ($i) => (float) $i->quantity * (float) $i->price),
                2
            );
        }

        return response()->json($response);
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'types'      => ['required', 'array', 'min:1'],
            'types.*'    => ['in:machinery,bookings,sales,inventory'],
        ]);

        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end   = Carbon::parse($validated['end_date'])->endOfDay();
        $types = $validated['types'];

        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new Language(Language::EN_US));
        $phpWord->setDefaultFontName(self::FONT_FAMILY);
        $phpWord->setDefaultFontSize(10);
        $phpWord->setDefaultParagraphStyle([
            'spaceAfter'  => 0,
            'spaceBefore' => 0,
            'lineHeight'  => 1.0,
        ]);

        if (in_array('machinery', $types, true)) {
            $machinery = Machinery::orderBy('machinery_name')->get();
            $this->addMachinerySection($phpWord, $machinery);
        }

        if (in_array('bookings', $types, true)) {
            $bookings = Booking::with(['machine', 'user'])
                ->where('status', 'completed')
                ->whereBetween('start_date', [$start, $end])
                ->orderBy('start_date')
                ->get();

            $this->addBookingsSection($phpWord, $bookings, $start, $end);
        }

        if (in_array('sales', $types, true)) {
            $sales = Sales::with('product')
                ->whereBetween('sale_date', [$start, $end])
                ->orderBy('sale_date')
                ->get();

            $this->addSalesSection($phpWord, $sales, $start, $end);
        }

        if (in_array('inventory', $types, true)) {
            $inventory = Inventory::orderBy('name')->get();
            $this->addInventorySection($phpWord, $inventory);
        }

        $filename = 'psareco-report-' . now()->format('Y-m-d_His') . '.docx';
        $tempPath = storage_path('app/' . $filename);

        IOFactory::createWriter($phpWord, 'Word2007')->save($tempPath);

        return response()
            ->download($tempPath, $filename)
            ->deleteFileAfterSend(true);
    }

    private function addMasthead(Section $section, string $subtitle): void
    {
        $header = $section->addHeader();

        $table = $header->addTable([
            'borderSize'        => 0,
            'borderColor'       => 'FFFFFF',
            'borderTopSize'     => 0,
            'borderBottomSize'  => 0,
            'borderLeftSize'    => 0,
            'borderRightSize'   => 0,
            'borderInsideHSize' => 0,
            'borderInsideVSize' => 0,
            'cellMargin'        => 0,
            'alignment'         => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
        ]);

        $table->addRow();

        $cellStyle = [
            'valign'      => 'center',
            'borderSize'  => 0,
            'borderColor' => 'FFFFFF',
        ];

        $logoCell = $table->addCell(1200, $cellStyle);
        if ($logo = $this->logoPath()) {
            $logoCell->addImage($logo, ['width' => 55, 'height' => 55, 'alignment' => 'center']);
        }

        $textCell = $table->addCell(9200, $cellStyle);
        $textCell->addText(
            'PSARECO FARM RESOURCE MANAGEMENT SYSTEM',
            ['bold' => true, 'size' => 12, 'color' => '000000', 'name' => self::FONT_FAMILY],
            ['alignment' => 'center']
        );
        $textCell->addText(
            strtoupper($subtitle),
            ['bold' => true, 'size' => 14, 'color' => '000000', 'name' => self::FONT_FAMILY],
            ['alignment' => 'center', 'spaceBefore' => 60]
        );
    }

    private function addMetaLine(Section $section, array $fields): void
    {
        foreach ($fields as $field) {
            $run = $section->addTextRun(['spaceAfter' => 40]);

            $run->addText($field['label'] . ': ', ['size' => 9, 'color' => self::LABEL_GRAY, 'name' => self::FONT_FAMILY]);
            $run->addText(
                $field['value'],
                [
                    'bold'  => true,
                    'size'  => 9,
                    'color' => ($field['emphasize'] ?? false) ? self::DARK_GREEN : '000000',
                    'name'  => self::FONT_FAMILY,
                ]
            );
        }

        $section->addText('', [], [
            'borderBottomSize'  => 18,
            'borderBottomColor' => self::DARK_GREEN,
            'spaceAfter'        => 200,
        ]);
    }
    private function addModuleFooter(Section $section, string $moduleLabel): void
    {
        $footer = $section->addFooter();
        $footer->addText(
            'PSARECO Farm Resource Management System – ' . $moduleLabel . ' Module',
            ['italic' => true, 'size' => 8, 'color' => self::LABEL_GRAY, 'name' => self::FONT_FAMILY]
        );
    }

    private function headerParagraphStyle(): array
    {
        return ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];
    }

    private function tableStyle(): array
    {
        return [
            'borderSize'  => 6,
            'borderColor' => self::BORDER_GREEN,
            'cellMargin'  => 40,
        ];
    }
    private function sectionStyle(): array
    {
        return [
            'marginLeft'   => 720,
            'marginRight'  => 720,
            'marginTop'    => 900,
            'marginBottom' => 900,
        ];
    }

    private function headerCellStyle(): array
    {
        return ['bgColor' => self::FILL_GREEN, 'valign' => 'center'];
    }

    private function headerFontStyle(): array
    {
        return ['bold' => true, 'color' => self::DARK_GREEN, 'size' => 9, 'name' => self::FONT_FAMILY];
    }

    private function cellFontStyle(): array
    {
        return ['size' => 9, 'name' => self::FONT_FAMILY];
    }

    private function totalRowStyle(): array
    {
        return ['bgColor' => self::FILL_GREEN];
    }

    private function totalFontStyle(): array
    {
        return ['bold' => true, 'size' => 9, 'color' => self::DARK_GREEN, 'name' => self::FONT_FAMILY];
    }

    private function numericParagraphStyle(): array
{
    return ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END];
}

    private function addMachinerySection(PhpWord $phpWord, $machinery): void
    {
        $section = $phpWord->addSection();
        $this->addMasthead($section, 'Machinery Inventory Report');
        $this->addModuleFooter($section, 'Machinery');

        $this->addMetaLine($section, [
            ['label' => 'Date Generated', 'value' => now()->format('M d, Y g:i A')],
            ['label' => 'Total Machinery', 'value' => (string) $machinery->count(), 'emphasize' => true],
        ]);

        $table = $section->addTable($this->tableStyle());

        $table->addRow(400);
        foreach (['Machinery', 'Model', 'Serial Number', 'Price (₱)', 'Status'] as $header) {
            $table->addCell(1600, $this->headerCellStyle())->addText($header, $this->headerFontStyle(), $this->headerParagraphStyle());
        }

        foreach ($machinery as $machine) {
            $table->addRow(100, ['exactHeight' => false]);
            $table->addCell(2200)->addText($machine->machinery_name, $this->cellFontStyle());
            $table->addCell(1800)->addText($machine->model ?? '-', $this->cellFontStyle());
            $table->addCell(2200)->addText($machine->serial_number ?? '-', $this->cellFontStyle());
            $table->addCell(1600)->addText(number_format((float) $machine->price, 2), $this->cellFontStyle(), $this->numericParagraphStyle());
            $table->addCell(1600)->addText($machine->status ?? '-', $this->cellFontStyle());
        }

        if ($machinery->isEmpty()) {
            $table->addRow();
            $cell = $table->addCell(9600);
            $cell->getStyle()->setGridSpan(5);
            $cell->addText('No machinery found.', ['italic' => true, 'size' => 9, 'color' => self::LABEL_GRAY, 'name' => self::FONT_FAMILY]);
        }
    }

    private function addBookingsSection(PhpWord $phpWord, $bookings, Carbon $start, Carbon $end): void
    {
        $section = $phpWord->addSection();
        $this->addMasthead($section, 'Bookings History Report');
        $this->addModuleFooter($section, 'Bookings');

        $bookingIncome = $bookings->sum('total_amount');

        $this->addMetaLine($section, [
            ['label' => 'Date Generated', 'value' => now()->format('M d, Y g:i A')],
            ['label' => 'Period', 'value' => $start->format('M d, Y') . ' - ' . $end->format('M d, Y')],
            ['label' => 'Total Records', 'value' => (string) $bookings->count()],
            ['label' => 'Total Income', 'value' => '₱' . number_format($bookingIncome, 2), 'emphasize' => true],
        ]);

        $table = $section->addTable($this->tableStyle());

        $table->addRow(400);
        foreach (['Machinery', 'Customer', 'Start Date', 'End Date', 'Days', 'Hours', 'Amount (₱)'] as $header) {
            $table->addCell(1600, $this->headerCellStyle())->addText($header, $this->headerFontStyle(), $this->headerParagraphStyle());
        }

        foreach ($bookings as $booking) {
             $table->addRow(100, ['exactHeight' => false]);
            $table->addCell(1800)->addText($booking->machine?->machinery_name ?? 'N/A', $this->cellFontStyle());
            $table->addCell(1800)->addText($booking->user?->name ?? 'N/A', $this->cellFontStyle());
            $table->addCell(1600)->addText($booking->start_date?->format('M d, Y') ?? '-', $this->cellFontStyle());
            $table->addCell(1600)->addText($booking->end_date?->format('M d, Y') ?? '-', $this->cellFontStyle());
            $table->addCell(1000)->addText((string) $booking->days, $this->cellFontStyle());
            $table->addCell(1200)->addText(number_format((float) $booking->total_hours, 2), $this->cellFontStyle(), $this->numericParagraphStyle());
            $table->addCell(1600)->addText(number_format((float) $booking->total_amount, 2), $this->cellFontStyle(), $this->numericParagraphStyle());
        }

        if ($bookings->isEmpty()) {
            $table->addRow();
            $cell = $table->addCell(11200);
            $cell->getStyle()->setGridSpan(7);
            $cell->addText('No completed bookings found for this period.', ['italic' => true, 'size' => 9, 'color' => self::LABEL_GRAY, 'name' => self::FONT_FAMILY]);
        } else {
            $table->addRow();
            $totalLabelCell = $table->addCell(9600, $this->totalRowStyle());
            $totalLabelCell->getStyle()->setGridSpan(6);
            $totalLabelCell->addText('Total Booking Income', $this->totalFontStyle());
            $table->addCell(1600, $this->totalRowStyle())->addText(number_format($bookingIncome, 2), $this->totalFontStyle());
        }
    }

    private function addSalesSection(PhpWord $phpWord, $sales, Carbon $start, Carbon $end): void
    {
        $section = $phpWord->addSection();
        $this->addMasthead($section, 'Sales History Report');
        $this->addModuleFooter($section, 'Sales');

        $salesIncome = $sales->sum('total');

        $this->addMetaLine($section, [
            ['label' => 'Date Generated', 'value' => now()->format('M d, Y g:i A')],
            ['label' => 'Total Records', 'value' => (string) $sales->count()],
            ['label' => 'Total Amount', 'value' => '₱' . number_format($salesIncome, 2), 'emphasize' => true],
        ]);

        $table = $section->addTable($this->tableStyle());

        $table->addRow(400);
        foreach (['Date and Time', 'Product', 'Quantity', 'Unit Price', 'Total', 'Buyer'] as $header) {
            $table->addCell(1600, $this->headerCellStyle())->addText($header, $this->headerFontStyle(), $this->headerParagraphStyle());
        }

        foreach ($sales as $sale) {
             $table->addRow(100, ['exactHeight' => false]);
            $table->addCell(1500)->addText($sale->sale_date?->format('M d, Y') ?? '-', $this->cellFontStyle());
            $table->addCell(2200)->addText($sale->product?->name ?? 'N/A', $this->cellFontStyle());
            $table->addCell(1200)->addText(number_format((int) $sale->quantity), $this->cellFontStyle(), $this->numericParagraphStyle());
            $table->addCell(1500)->addText(number_format((float) $sale->price, 2), $this->cellFontStyle(), $this->numericParagraphStyle());
            $table->addCell(1500)->addText(number_format((float) $sale->total, 2), $this->cellFontStyle(), $this->numericParagraphStyle());
            $table->addCell(1800)->addText($sale->buyer_name ?? 'N/A', $this->cellFontStyle());
        }

        if ($sales->isEmpty()) {
            $table->addRow();
            $cell = $table->addCell(9700);
            $cell->getStyle()->setGridSpan(6);
            $cell->addText('No sales transactions found for this period.', ['italic' => true, 'size' => 9, 'color' => self::LABEL_GRAY, 'name' => self::FONT_FAMILY]);
        } else {
            $table->addRow();
            $totalLabelCell = $table->addCell(8200, $this->totalRowStyle());
            $totalLabelCell->getStyle()->setGridSpan(5);
            $totalLabelCell->addText('TOTAL SALES', $this->totalFontStyle());
            $table->addCell(1500, $this->totalRowStyle())->addText(number_format($salesIncome, 2), $this->totalFontStyle());
        }
    }

    private function addInventorySection(PhpWord $phpWord, $inventory): void
    {
        $section = $phpWord->addSection();
        $this->addMasthead($section, 'Inventory Report');
        $this->addModuleFooter($section, 'Inventory');

        $inventoryValue = $inventory->sum(fn ($item) => (float) $item->quantity * (float) $item->price);

        $this->addMetaLine($section, [
            ['label' => 'Date Generated', 'value' => now()->format('M d, Y g:i A')],
            ['label' => 'Existing Items', 'value' => (string) $inventory->count()],
            ['label' => 'Inventory Value', 'value' => '₱' . number_format($inventoryValue, 2), 'emphasize' => true],
        ]);

        $table = $section->addTable($this->tableStyle());

        $table->addRow(400);
        foreach (['Name', 'Type', 'Quantity', 'Unit', 'Price (₱)', 'Value (₱)', 'Reorder Level', 'Expiration'] as $header) {
            $table->addCell(1400, $this->headerCellStyle())->addText($header, $this->headerFontStyle(), $this->headerParagraphStyle());
        }

        $lowStockFontStyle = ['size' => 9, 'color' => self::LOW_STOCK_RED, 'bold' => true, 'name' => self::FONT_FAMILY];

        foreach ($inventory as $item) {
            $isLowStock = $item->quantity <= $item->reorder_level;

            $table->addRow(100, ['exactHeight' => false]);
            $table->addCell(1600)->addText($item->name, $this->cellFontStyle());
            $table->addCell(1300)->addText($item->type ?? '-', $this->cellFontStyle());
            $table->addCell(1200)->addText(number_format((float) $item->quantity, 2), $isLowStock ? $lowStockFontStyle : $this->cellFontStyle(), $this->numericParagraphStyle());
            $table->addCell(1000)->addText($item->unit ?? '-', $this->cellFontStyle(), $this->numericParagraphStyle());
            $table->addCell(1400)->addText(number_format((float) $item->price, 2), $this->cellFontStyle(), $this->numericParagraphStyle());
            $table->addCell(1400)->addText(number_format((float) $item->quantity * (float) $item->price, 2), $this->cellFontStyle(), $this->numericParagraphStyle());
            $table->addCell(1400)->addText(number_format((float) $item->reorder_level), $this->cellFontStyle(), $this->numericParagraphStyle());
            $table->addCell(1500)->addText($item->expiration_date?->format('M d, Y') ?? '-', $this->cellFontStyle());
        }

        if ($inventory->isEmpty()) {
            $table->addRow();
            $cell = $table->addCell(11200);
            $cell->getStyle()->setGridSpan(8);
            $cell->addText('No existing inventory found.', ['italic' => true, 'size' => 9, 'color' => self::LABEL_GRAY, 'name' => self::FONT_FAMILY]);
        }

        $section->addTextBreak(1);
        $section->addText(
            'Items in red indicate quantity at or below reorder level.',
            ['italic' => true, 'size' => 8, 'color' => self::LABEL_GRAY, 'name' => self::FONT_FAMILY]
        );
    }
}
