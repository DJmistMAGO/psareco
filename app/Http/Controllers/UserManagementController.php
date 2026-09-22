<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class UserManagementController extends Controller
{
    private function userSummary(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'contact_number' => $user->contact_number,
            'address' => $user->address,
            'position' => $user->position,
            'status' => $user->status,
            'must_change_password' => $user->must_change_password,
            'roles' => $user->getRoleNames()->toArray(),
            'created_at' => $user->created_at,
        ];
    }

    public function index()
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })
            ->orderBy('name')
            ->get();

        $activeUsers = $users
            ->where('status', 'active')
            ->values()
            ->map(fn(User $user) => $this->userSummary($user));

        $inactiveUsers = $users
            ->where('status', 'inactive')
            ->values()
            ->map(fn(User $user) => $this->userSummary($user));

        $activeFarmers = $activeUsers
            ->filter(fn($user) => in_array('farmer', $user['roles'], true))
            ->values();

        $activeOfficers = $activeUsers
            ->filter(fn($user) => in_array('officer', $user['roles'], true))
            ->values();

        $inactiveFarmers = $inactiveUsers
            ->filter(fn($user) => in_array('farmer', $user['roles'], true))
            ->values();

        $inactiveOfficers = $inactiveUsers
            ->filter(fn($user) => in_array('officer', $user['roles'], true))
            ->values();

        return view('admin.users', compact(
            'activeUsers',
            'inactiveUsers',
            'activeFarmers',
            'activeOfficers',
            'inactiveFarmers',
            'inactiveOfficers'
        ));
    }

    private function userValidationRules(?User $user = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . ($user?->id ?? 'NULL')],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:100'],
            'role' => ['required', 'in:officer,farmer'],
            'status' => ['sometimes', 'required', 'in:active,inactive'],
        ];
    }

    public function addUser(Request $request)
    {
        $validated = $request->validate(array_merge([
            'password' => ['required', 'string', 'min:8'],
        ], $this->userValidationRules()));

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'contact_number' => $validated['contact_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'position' => $validated['position'] ?? null,
            'status' => 'active',
            'must_change_password' => true,
        ]);

        $user->assignRole($validated['role']);

        return redirect()->back()->with(
            'success',
            'User account created successfully. They\'ll be asked to set a new password on first login.'
        );
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('admin')) {
            return redirect()->back()->with('error', 'Admin accounts cannot be edited from User Management.');
        }

        $validated = $request->validate($this->userValidationRules($user));

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact_number' => $validated['contact_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'position' => $validated['position'] ?? null,
            'status' => $validated['status'] ?? $user->status,
        ]);

        if (!$user->hasRole($validated['role'])) {
            $user->syncRoles([$validated['role']]);
        }

        return redirect()->back()->with('success', 'User account updated successfully.');
    }

    public function deactivateUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('admin')) {
            return redirect()->back()->with('error', 'Admin accounts cannot be deactivated from User Management.');
        }

        $user->status = 'inactive';
        $user->save();

        return redirect()->back()->with('success', 'User account deactivated successfully.');
    }

    public function reactivateUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('admin')) {
            return redirect()->back()->with('error', 'Admin accounts cannot be reactivated from User Management.');
        }

        $user->status = 'active';
        $user->save();

        return redirect()->back()->with('success', 'User account reactivated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('admin')) {
            return redirect()->back()->with('error', 'Admin accounts cannot be deleted from User Management.');
        }

        if ($user->status === 'active') {
            return redirect()->back()->with('error', 'Only deactivated users can be permanently deleted.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User account permanently deleted successfully.');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('admin')) {
            return redirect()->back()->with('error', 'Admin accounts cannot have their passwords reset from User Management.');
        }

        $newPassword = 'DefaultPass123';

        $user->password = bcrypt($newPassword);
        $user->must_change_password = true;
        $user->save();

        return redirect()->back()->with('success', "User's password has been reset successfully. The new password is: {$newPassword}");
    }



    public function exportXlsx()
    {
        $users = User::orderBy('status')
            ->orderBy('name')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('User List');

        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_LEGAL)
            ->setFitToWidth(1)
            ->setFitToHeight(0);

        $sheet->getPageMargins()
            ->setTop(0.45)
            ->setRight(0.45)
            ->setBottom(0.55)
            ->setLeft(0.45);

        $sheet->getPageSetup()->setHorizontalCentered(true);

        $darkGreen = '538135';
        $borderGreen = 'A8D08D';
        $fillGreen = 'E2EFD9';
        $labelGray = '808080';

        $widths = [
            'A' => 8,
            'B' => 24,
            'C' => 30,
            'D' => 13,
            'E' => 12,
            'F' => 18,
            'G' => 34,
            'H' => 20,
            'I' => 20,
        ];

        foreach ($widths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        $logoPath = public_path('assets/images/PSARECO_logo.png');

        if (file_exists($logoPath)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

            $drawing->setName('PSARECO Logo');
            $drawing->setDescription('PSARECO Logo');
            $drawing->setPath($logoPath);
            $drawing->setHeight(75);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(8);
            $drawing->setOffsetY(5);
            $drawing->setWorksheet($sheet);
        }

        $sheet->mergeCells('A1:B3');
        $sheet->mergeCells('C1:I1');
        $sheet->mergeCells('C2:I2');
        $sheet->mergeCells('C3:I3');

        $sheet->setCellValue(
            'C1',
            'POLOT SOMAGONGSONG AGRARIAN REFORM COOPERATIVE'
        );

        $sheet->setCellValue(
            'C2',
            'USER MANAGEMENT REPORT'
        );

        $sheet->setCellValue(
            'C3',
            'User Account List'
        );

        $sheet->getStyle('C1:I3')
            ->getFont()
            ->setName('Arial');

        $sheet->getStyle('C1:I1')
            ->getFont()
            ->setBold(true)
            ->setSize(14)
            ->getColor()
            ->setARGB($darkGreen);

        $sheet->getStyle('C2:I2')
            ->getFont()
            ->setBold(true)
            ->setSize(12)
            ->getColor()
            ->setARGB($darkGreen);

        $sheet->getStyle('C3:I3')
            ->getFont()
            ->setSize(10)
            ->getColor()
            ->setARGB($labelGray);

        $sheet->getStyle('C1:I3')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getRowDimension(1)->setRowHeight(26);
        $sheet->getRowDimension(2)->setRowHeight(23);
        $sheet->getRowDimension(3)->setRowHeight(21);

        $sheet->mergeCells('A5:I5');

        $sheet->setCellValue(
            'A5',
            'Generated: ' . now()->format('F d, Y h:i A')
        );

        $sheet->getStyle('A5:I5')
            ->getFont()
            ->setName('Arial')
            ->setSize(9)
            ->getColor()
            ->setARGB($labelGray);

        $sheet->getStyle('A5:I5')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->mergeCells('A6:I6');

        $sheet->getStyle('A6:I6')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB($darkGreen);

        $sheet->getRowDimension(6)->setRowHeight(5);

        $headerRow = 8;

        $headers = [
            'ID',
            'Name',
            'Email',
            'Role',
            'Status',
            'Contact Number',
            'Address',
            'Position',
            'Created At',
        ];

        foreach ($headers as $index => $header) {
            $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(
                $index + 1
            );

            $sheet->setCellValue(
                $column . $headerRow,
                $header
            );
        }

        $headerRange = "A{$headerRow}:I{$headerRow}";

        $sheet->getStyle($headerRange)
            ->getFont()
            ->setName('Arial')
            ->setBold(true)
            ->setSize(10)
            ->getColor()
            ->setARGB('FFFFFF');

        $sheet->getStyle($headerRange)
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB($darkGreen);

        $sheet->getStyle($headerRange)
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);

        $sheet->getStyle($headerRange)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->getColor()
            ->setARGB($borderGreen);

        $sheet->getRowDimension($headerRow)->setRowHeight(32);

        $row = $headerRow + 1;

        foreach ($users as $user) {
            $sheet->setCellValue("A{$row}", $user->id);
            $sheet->setCellValue("B{$row}", $user->name);
            $sheet->setCellValue("C{$row}", $user->email);
            $sheet->setCellValue(
                "D{$row}",
                $user->getRoleNames()->first() ?? 'N/A'
            );
            $sheet->setCellValue(
                "E{$row}",
                ucfirst($user->status)
            );
            $sheet->setCellValue(
                "F{$row}",
                $user->contact_number ?? ''
            );
            $sheet->setCellValue(
                "G{$row}",
                $user->address ?? ''
            );
            $sheet->setCellValue(
                "H{$row}",
                $user->position ?? ''
            );
            $sheet->setCellValue(
                "I{$row}",
                $user->created_at?->format('Y-m-d H:i:s') ?? ''
            );

            $dataRange = "A{$row}:I{$row}";

            $sheet->getStyle($dataRange)
                ->getFont()
                ->setName('Arial')
                ->setSize(10);

            $sheet->getStyle($dataRange)
                ->getAlignment()
                ->setVertical(Alignment::VERTICAL_CENTER)
                ->setWrapText(true);

            $sheet->getStyle("A{$row}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("D{$row}:E{$row}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle($dataRange)
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)
                ->getColor()
                ->setARGB($borderGreen);

            $row++;
        }

        if ($users->count() > 0) {
            for (
                $dataRow = $headerRow + 1;
                $dataRow < $row;
                $dataRow++
            ) {
                if ($dataRow % 2 === 0) {
                    $sheet->getStyle("A{$dataRow}:I{$dataRow}")
                        ->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('F7FBF3');
                }
            }
        } else {
            $sheet->mergeCells("A{$row}:I{$row}");

            $sheet->setCellValue(
                "A{$row}",
                'No user records found.'
            );

            $sheet->getStyle("A{$row}:I{$row}")
                ->getFont()
                ->setName('Arial')
                ->setSize(10)
                ->getColor()
                ->setARGB($labelGray);

            $sheet->getStyle("A{$row}:I{$row}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                ->setVertical(Alignment::VERTICAL_CENTER);

            $sheet->getStyle("A{$row}:I{$row}")
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN)
                ->getColor()
                ->setARGB($borderGreen);

            $row++;
        }

        $lastDataRow = max(
            $headerRow + 1,
            $row - 1
        );

        $sheet->setAutoFilter(
            "A{$headerRow}:I{$lastDataRow}"
        );

        $sheet->freezePane('A9');

        $signatoryRow = $row + 3;

        $sheet->setCellValue(
            "A{$signatoryRow}",
            'Generated by:'
        );

        $sheet->getStyle("A{$signatoryRow}")
            ->getFont()
            ->setName('Arial')
            ->setSize(9)
            ->getColor()
            ->setARGB($labelGray);

        $signatoryLineRow = $signatoryRow + 2;

        $sheet->mergeCells(
            "A{$signatoryLineRow}:C{$signatoryLineRow}"
        );

        $sheet->setCellValue(
            "A{$signatoryLineRow}",
            '______________________________'
        );

        $sheet->getStyle(
            "A{$signatoryLineRow}:C{$signatoryLineRow}"
        )
            ->getFont()
            ->setName('Arial')
            ->setBold(true)
            ->setSize(10);

        $sheet->mergeCells(
            "A" . ($signatoryLineRow + 1) .
            ":C" . ($signatoryLineRow + 1)
        );

        $generatedBy = auth()->user()?->name ?? 'N/A';

        $sheet->setCellValue(
            "A" . ($signatoryLineRow + 1),
            strtoupper($generatedBy)
        );

        $sheet->getStyle(
            "A" . ($signatoryLineRow + 1) .
            ":C" . ($signatoryLineRow + 1)
        )
            ->getFont()
            ->setName('Arial')
            ->setBold(true)
            ->setSize(9);

        $sheet->getHeaderFooter()
            ->setOddFooter(
                '&LPSARECO User Management Report' .
                '&RPage &P of &N'
            );

        $sheet->getHeaderFooter()
            ->setOddHeader('');

        $sheet->getPageSetup()->setPrintArea(
            "A1:I" . ($signatoryLineRow + 2)
        );

        $fileName = 'psareco-user-list-' .
            now()->format('Y-m-d-His') .
            '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            $fileName,
            [
                'Content-Type' =>
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }
}
