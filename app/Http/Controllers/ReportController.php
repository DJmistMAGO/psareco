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
            'types.*'    => ['in:machinery,bookings,sales,inventory,expiring'],
        ]);

        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end   = Carbon::parse($validated['end_date'])->endOfDay();
        $types = $validated['types'];

        $response = [];

        if (\in_array('machinery', $types, true)) {
            $machinery = Machinery::orderBy('machinery_name')->get();

            $response['machinery'] = $machinery->map(fn ($m) => [
                'machinery_name' => $m->machinery_name,
                'model'          => $m->model,
                'serial_number'  => $m->serial_number,
                'price'          => number_format((float) $m->price, 2),
                'status'         => $m->status,
            ]);
        }

        if (\in_array('bookings', $types, true)) {
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

        if (\in_array('sales', $types, true)) {
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

        if (\in_array('inventory', $types, true)) {
            $inventory = Inventory::orderBy('name')->get();

            $response['inventory'] = $inventory->map(fn ($i) => [
                'name'             => $i->name,
                'type'             => $i->type,
                'quantity'         => number_format((float) $i->quantity, 2),
                'unit'             => $i->unit,
                'description'      => $i->description ?? 'N/A',
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

        if (\in_array('expiring', $types, true)) {
            $expiringInventory = Inventory::whereNotNull('expiration_date')
                ->whereBetween('expiration_date', [$start, $end])
                ->orderBy('expiration_date')
                ->get();

            $response['expiring_inventory'] = $expiringInventory->map(fn ($i) => [
                'name'             => $i->name,
                'type'             => $i->type,
                'quantity'         => number_format((float) $i->quantity, 2),
                'unit'             => $i->unit,
                'description'      => $i->description ?? 'N/A',
                'price'            => number_format((float) $i->price, 2),
                'inventory_value'  => number_format((float) $i->quantity * (float) $i->price, 2),
                'reorder_level'    => number_format((float) $i->reorder_level, 2),
                'expiration'       => $i->expiration_date
                    ? Carbon::parse($i->expiration_date)->format('M d, Y')
                    : 'N/A',
                'low_stock'        => (float) $i->quantity <= (float) $i->reorder_level,
            ])->values();
        }

        return response()->json($response);
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'types'      => ['required', 'array', 'min:1'],
            'types.*'    => ['in:machinery,bookings,sales,inventory,expiring'],
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

        if (\in_array('machinery', $types, true)) {
            $machinery = Machinery::orderBy('machinery_name')->get();
            $this->addMachinerySection($phpWord, $machinery);
        }

        if (\in_array('bookings', $types, true)) {
            $bookings = Booking::with(['machine', 'user'])
                ->where('status', 'completed')
                ->whereBetween('start_date', [$start, $end])
                ->orderBy('start_date')
                ->get();

            $this->addBookingsSection($phpWord, $bookings, $start, $end);
        }

        if (\in_array('sales', $types, true)) {
            $sales = Sales::with('product')
                ->whereBetween('sale_date', [$start, $end])
                ->orderBy('sale_date')
                ->get();

            $this->addSalesSection($phpWord, $sales);
        }

        if (\in_array('inventory', $types, true)) {
            $inventory = Inventory::orderBy('name')->get();
            $this->addInventorySection($phpWord, $inventory);
        }

        if (\in_array('expiring', $types, true)) {
            $expiringInventory = Inventory::whereNotNull('expiration_date')
                ->whereBetween('expiration_date', [now()->startOfDay(), now()->addDays(30)->endOfDay()])
                ->orderBy('expiration_date')
                ->get();

            $this->addExpiringSection($phpWord, $expiringInventory);
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
            'cellMargin'  => 50,

            'alignment'   => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'layout'      => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED,
            'width'       => 100,
        ];
    }

    private function sectionStyle(): array
    {
        return [
            'orientation' => \PhpOffice\PhpWord\Style\Section::ORIENTATION_LANDSCAPE,

            'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(13),
            'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::inchToTwip(8.5),

            'marginLeft'  => 720,
            'marginRight' => 720,

            'marginTop'    => 700,
            'marginBottom' => 700,

            'headerHeight' => 300,
            'footerHeight' => 300,
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

    private function addSignatory(Section $section): void
    {
        $user = auth()->user();
        $name = $user?->name ?? 'N/A';
        $section->addTextBreak(2);
        $section->addText(
            'Generated by:',
            [ 'size' => 9, 'color' => self::LABEL_GRAY, 'name' => self::FONT_FAMILY, ],
            [ 'spaceAfter' => 250, ]
        );

        $section->addText(
            '______________________________',
            [ 'bold' => true, 'size' => 10, 'name' => self::FONT_FAMILY, ],
            [ 'spaceAfter' => 40, ]
        );

        $section->addText(
            strtoupper($name),
            [ 'bold' => true, 'size' => 9, 'name' => self::FONT_FAMILY, ],
            [ 'spaceAfter' => 0, ]
        );
    }

    private function addMachinerySection(PhpWord $phpWord, $machinery): void
    {
        $section = $phpWord->addSection($this->sectionStyle());
        $this->addMasthead($section, 'Machinery Inventory Report');
        $this->addModuleFooter($section, 'Machinery');

        $this->addMetaLine($section, [
            ['label' => 'Date Generated', 'value' => now()->format('M d, Y g:i A')],
            ['label' => 'Total Machinery', 'value' => (string) $machinery->count(), 'emphasize' => true],
        ]);

        $table = $section->addTable($this->tableStyle());

        $columns = [
            ['Machinery', 3200],
            ['Model', 2800],
            ['Serial Number', 3400],
            ['Price (₱)', 2200],
            ['Status', 5680],
        ];

        $table->addRow(400);

        foreach ($columns as [$header, $width]) {
            $table->addCell($width, $this->headerCellStyle())
                ->addText(
                    $header,
                    $this->headerFontStyle(),
                    $this->headerParagraphStyle()
                );
        }

        foreach ($machinery as $machine) {
            $table->addRow(100, ['exactHeight' => false]);

            $table->addCell(3200)
                ->addText($machine->machinery_name, $this->cellFontStyle());

            $table->addCell(2800)
                ->addText($machine->model ?? '-', $this->cellFontStyle());

            $table->addCell(3400)
                ->addText($machine->serial_number ?? '-', $this->cellFontStyle());

            $table->addCell(2200)
                ->addText(
                    number_format((float) $machine->price, 2),
                    $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );

            $table->addCell(5680)
                ->addText($machine->status ?? '-', $this->cellFontStyle());
        }

        if ($machinery->isEmpty()) {
            $table->addRow();

            $cell = $table->addCell(17280);
            $cell->getStyle()->setGridSpan(5);

            $cell->addText(
                'No machinery found.',
                [
                    'italic' => true,
                    'size' => 9,
                    'color' => self::LABEL_GRAY,
                    'name' => self::FONT_FAMILY,
                ]
            );
        }

        $this->addSignatory($section);
    }

    private function addBookingsSection(PhpWord $phpWord, $bookings, Carbon $start, Carbon $end): void
    {
        $section = $phpWord->addSection($this->sectionStyle());
        $this->addMasthead($section, 'Bookings History Report');
        $this->addModuleFooter($section, 'Bookings');

        $bookingIncome = $bookings->sum('total_amount');

        $this->addMetaLine($section, [
            ['label' => 'Date Generated', 'value' => now()->format('M d, Y g:i A')],
            ['label' => 'Period', 'value' => $start->format('M d, Y') . ' - ' . $end->format('M d, Y')],
            ['label' => 'Total Records', 'value' => (string) $bookings->count()],
            ['label' => 'Total Income', 'value' => '₱ ' . number_format($bookingIncome, 2), 'emphasize' => true],
        ]);

        $table = $section->addTable($this->tableStyle());

        $columns = [
            ['Machinery', 3400],
            ['Customer', 3800],
            ['Start Date', 2200],
            ['End Date', 2200],
            ['Days', 900],
            ['Hours', 1800],
            ['Amount (₱)', 2980],
        ];

        $table->addRow(400);

        foreach ($columns as [$header, $width]) {
            $table->addCell($width, $this->headerCellStyle())
                ->addText(
                    $header,
                    $this->headerFontStyle(),
                    $this->headerParagraphStyle()
                );
        }

        foreach ($bookings as $booking) {
            $table->addRow(100, ['exactHeight' => false]);

            $table->addCell(3400)
                ->addText($booking->machine?->machinery_name ?? 'N/A', $this->cellFontStyle());

            $table->addCell(3800)
                ->addText($booking->user?->name ?? 'N/A', $this->cellFontStyle());

            $table->addCell(2200)
                ->addText($booking->start_date?->format('M d, Y') ?? '-', $this->cellFontStyle());

            $table->addCell(2200)
                ->addText($booking->end_date?->format('M d, Y') ?? '-', $this->cellFontStyle());

            $table->addCell(900)
                ->addText((string) $booking->days, $this->cellFontStyle());

            $table->addCell(1800)
                ->addText(
                    '₱ ' . number_format((float) $booking->total_hours, 2),
                    $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );

            $table->addCell(2980)
                ->addText(
                    '₱ ' . number_format((float) $booking->total_amount, 2),
                    $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );
        }

        if ($bookings->isEmpty()) {
            $table->addRow();

            $cell = $table->addCell(17280);
            $cell->getStyle()->setGridSpan(7);

            $cell->addText(
                'No completed bookings found for this period.',
                [
                    'italic' => true,
                    'size' => 9,
                    'color' => self::LABEL_GRAY,
                    'name' => self::FONT_FAMILY,
                ]
            );
        } else {
            $table->addRow();

            // First 6 columns = 14300 twips
            $totalLabelCell = $table->addCell(14300, $this->totalRowStyle());
            $totalLabelCell->getStyle()->setGridSpan(6);

            $totalLabelCell->addText(
                'Total Booking Income',
                $this->totalFontStyle()
            );

            $table->addCell(2980, $this->totalRowStyle())
                ->addText(
                    '₱ ' . number_format($bookingIncome, 2),
                    $this->totalFontStyle()
                );
        }

        $this->addSignatory($section);
    }

    private function addSalesSection(PhpWord $phpWord, $sales): void
    {
        $section = $phpWord->addSection($this->sectionStyle());
        $this->addMasthead($section, 'Sales History Report');
        $this->addModuleFooter($section, 'Sales');

        $salesIncome = $sales->sum('total');

        $this->addMetaLine($section, [
            ['label' => 'Date Generated', 'value' => now()->format('M d, Y g:i A')],
            ['label' => 'Total Records', 'value' => (string) $sales->count()],
            ['label' => 'Total Amount', 'value' => '₱ ' . number_format($salesIncome, 2), 'emphasize' => true],
        ]);

        $table = $section->addTable($this->tableStyle());

        $columns = [
            ['Date and Time', 2800],
            ['Product', 3900],
            ['Quantity', 1100],
            ['Unit Price', 1900],
            ['Total', 1900],
            ['Buyer', 5680],
        ];

        $table->addRow(400);

        foreach ($columns as [$header, $width]) {
            $table->addCell($width, $this->headerCellStyle())
                ->addText(
                    $header,
                    $this->headerFontStyle(),
                    $this->headerParagraphStyle()
                );
        }

        foreach ($sales as $sale) {
            $table->addRow(100, ['exactHeight' => false]);

            $table->addCell(2800)
                ->addText($sale->sale_date?->format('M d, Y g:i A') ?? '-', $this->cellFontStyle());

            $table->addCell(3900)
                ->addText($sale->product?->name ?? 'N/A', $this->cellFontStyle());

            $table->addCell(1100)
                ->addText(number_format((int) $sale->quantity), $this->cellFontStyle());

            $table->addCell(1900)
                ->addText(
                    '₱ ' . number_format((float) $sale->price, 2),
                    $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );

            $table->addCell(1900)
                ->addText(
                    '₱ ' . number_format((float) $sale->total, 2),
                    $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );

            $table->addCell(5680)
                ->addText($sale->buyer_name ?? 'N/A', $this->cellFontStyle());
        }

        if ($sales->isEmpty()) {
            $table->addRow();

            $cell = $table->addCell(17280);
            $cell->getStyle()->setGridSpan(6);

            $cell->addText(
                'No sales transactions found for this period.',
                [
                    'italic' => true,
                    'size' => 9,
                    'color' => self::LABEL_GRAY,
                    'name' => self::FONT_FAMILY,
                ]
            );
        } else {
            $table->addRow();

            // First 5 columns = 11600 twips
            $totalLabelCell = $table->addCell(11600, $this->totalRowStyle());
            $totalLabelCell->getStyle()->setGridSpan(5);

            $totalLabelCell->addText(
                'TOTAL SALES',
                $this->totalFontStyle()
            );

            $table->addCell(5680, $this->totalRowStyle())
                ->addText(
                    '₱ ' . number_format($salesIncome, 2),
                    $this->totalFontStyle()
                );
        }

        $this->addSignatory($section);
    }

    private function addInventorySection(PhpWord $phpWord, $inventory): void
    {
        $section = $phpWord->addSection($this->sectionStyle());
        $this->addMasthead($section, 'Inventory Report');
        $this->addModuleFooter($section, 'Inventory');

        $inventoryValue = $inventory->sum(
            fn ($item) => (float) $item->quantity * (float) $item->price
        );

        $this->addMetaLine($section, [
            ['label' => 'Date Generated', 'value' => now()->format('M d, Y g:i A')],
            ['label' => 'Existing Items', 'value' => (string) $inventory->count()],
            ['label' => 'Inventory Value', 'value' => '₱ ' . number_format($inventoryValue, 2), 'emphasize' => true],
        ]);

        $table = $section->addTable($this->tableStyle());

        $columns = [
            ['Name', 3600],
            ['Type', 1400],
            ['Quantity', 1000],
            ['Unit', 850],
            ['Description', 3600],
            ['Price (₱)', 1500],
            ['Value (₱)', 1650],
            ['Reorder Level', 1700],
            ['Expiration', 1980],
        ];

        $table->addRow(400);

        foreach ($columns as [$header, $width]) {
            $table->addCell($width, $this->headerCellStyle())
                ->addText(
                    $header,
                    $this->headerFontStyle(),
                    $this->headerParagraphStyle()
                );
        }

        $lowStockFontStyle = [
            'size' => 9,
            'color' => self::LOW_STOCK_RED,
            'bold' => true,
            'name' => self::FONT_FAMILY,
        ];

        foreach ($inventory as $item) {
            $isLowStock = $item->quantity <= $item->reorder_level;

            $table->addRow(100, ['exactHeight' => false]);

            $table->addCell(3600)
                ->addText($item->name, $this->cellFontStyle());

            $table->addCell(1400)
                ->addText($item->type ?? '-', $this->cellFontStyle());

            $table->addCell(1000)
                ->addText(
                    number_format((float) $item->quantity, 2),
                    $isLowStock ? $lowStockFontStyle : $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );

            $table->addCell(850)
                ->addText(
                    $item->unit ?? '-',
                    $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );

            $table->addCell(3600)
                ->addText(
                    $item->description ?? '-',
                    $this->cellFontStyle()
                );

            $table->addCell(1500)
                ->addText(
                    '₱ ' . number_format((float) $item->price, 2),
                    $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );

            $table->addCell(1650)
                ->addText(
                    '₱ ' . number_format(
                        (float) $item->quantity * (float) $item->price,
                        2
                    ),
                    $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );

            $table->addCell(1700)
                ->addText(
                    number_format((float) $item->reorder_level, 2),
                    $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );

            $table->addCell(1980)
                ->addText(
                    $item->expiration_date?->format('M d, Y') ?? '-',
                    $this->cellFontStyle()
                );
        }

        if ($inventory->isEmpty()) {
            $table->addRow();

            $cell = $table->addCell(17280);
            $cell->getStyle()->setGridSpan(9);

            $cell->addText(
                'No existing inventory found.',
                [
                    'italic' => true,
                    'size' => 9,
                    'color' => self::LABEL_GRAY,
                    'name' => self::FONT_FAMILY,
                ]
            );
        }

        $section->addTextBreak(1);

        $section->addText(
            'Items in red indicate quantity at or below reorder level.',
            [
                'italic' => true,
                'size' => 8,
                'color' => self::LABEL_GRAY,
                'name' => self::FONT_FAMILY,
            ]
        );

        $this->addSignatory($section);
    }

    private function addExpiringSection(PhpWord $phpWord, $inventory): void
    {
        $section = $phpWord->addSection($this->sectionStyle());
        $this->addMasthead($section, 'Expiring Inventory Report');
        $this->addModuleFooter($section, 'Inventory');

        $expiringItems = $inventory->filter(
            fn ($item) =>
                $item->expiration_date &&
                $item->expiration_date->isFuture() &&
                $item->expiration_date->diffInDays(now()) <= 30
        );

        $this->addMetaLine($section, [
            ['label' => 'Date Generated', 'value' => now()->format('M d, Y g:i A')],
            ['label' => 'Expiring Items', 'value' => (string) $expiringItems->count(), 'emphasize' => true],
        ]);

        $table = $section->addTable($this->tableStyle());

        $columns = [
            ['Name', 3000],
            ['Type', 1500],
            ['Quantity', 1100],
            ['Description', 2280],
            ['Unit', 900],
            ['Price (₱)', 1700],
            ['Value (₱)', 1900],
            ['Reorder Level', 1900],
            ['Expiration', 3000],
        ];

        $table->addRow(400);

        foreach ($columns as [$header, $width]) {
            $table->addCell($width, $this->headerCellStyle())
                ->addText(
                    $header,
                    $this->headerFontStyle(),
                    $this->headerParagraphStyle()
                );
        }

        foreach ($expiringItems as $item) {
            $table->addRow(100, ['exactHeight' => false]);

            $table->addCell(3000)
                ->addText($item->name, $this->cellFontStyle());

            $table->addCell(1500)
                ->addText($item->type ?? '-', $this->cellFontStyle());

            $table->addCell(1100)
                ->addText(
                    number_format((float) $item->quantity, 2),
                    $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );

            $table->addCell(2280)
                ->addText(
                    $item->description ?? '-',
                    $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );

            $table->addCell(900)
                ->addText(
                    $item->unit ?? '-',
                    $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );

            $table->addCell(1700)
                ->addText(
                    '₱ ' . number_format((float) $item->price, 2),
                    $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );

            $table->addCell(1900)
                ->addText(
                    '₱ ' . number_format(
                        (float) $item->quantity * (float) $item->price,
                        2
                    ),
                    $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );

            $table->addCell(1900)
                ->addText(
                    number_format((float) $item->reorder_level, 2),
                    $this->cellFontStyle(),
                    $this->numericParagraphStyle()
                );

            $table->addCell(3000)
                ->addText(
                    $item->expiration_date?->format('M d, Y') ?? '-',
                    $this->cellFontStyle()
                );
        }

        if ($expiringItems->isEmpty()) {
            $table->addRow();

            $cell = $table->addCell(17280);
            $cell->getStyle()->setGridSpan(8);

            $cell->addText(
                'No expiring inventory found within the next 30 days.',
                [
                    'italic' => true,
                    'size' => 9,
                    'color' => self::LABEL_GRAY,
                    'name' => self::FONT_FAMILY,
                ]
            );
        }

        $this->addSignatory($section);
    }
}
