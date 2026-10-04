<?php

namespace Tests\Feature;

use App\Models\Machinery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Tests\TestCase;
use ZipArchive;

class MachineryReportDateFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_machinery_preview_and_report_respect_the_selected_date_range(): void
    {
        $role = Role::create(['name' => 'officer', 'guard_name' => 'web']);
        /** @var User $officer */
        $officer = User::factory()->create([
            'status' => 'active',
            'must_change_password' => false,
        ]);
        $officer->assignRole($role);
        $this->actingAs($officer);

        $this->createMachinery('In Range Equipment', '2026-05-15 12:00:00');
        $this->createMachinery('Out of Range Equipment', '2026-05-16 12:00:00');

        $filters = [
            'start_date' => '2026-05-15',
            'end_date' => '2026-05-15',
            'types' => ['machinery'],
        ];

        $this->getJson(route('reports.preview', $filters))
            ->assertOk()
            ->assertJsonCount(1, 'machinery')
            ->assertJsonPath('machinery.0.machinery_name', 'In Range Equipment');

        $reportFilters = $filters;
        $reportFilters['types'] = ['machinery', 'bookings', 'sales', 'inventory', 'expiring'];
        $report = $this->get(route('reports.generate', $reportFilters));
        $report->assertDownload();

        /** @var BinaryFileResponse $download */
        $download = $report->baseResponse;
        $zip = new ZipArchive();
        $this->assertSame(true, $zip->open($download->getFile()->getPathname()));
        $document = $zip->getFromName('word/document.xml');
        $zip->close();

        $this->assertStringContainsString('In Range Equipment', $document);
        $this->assertStringNotContainsString('Out of Range Equipment', $document);
        $this->assertSame(5, substr_count($document, 'Period:'));
        $this->assertStringContainsString('May 15, 2026 - May 15, 2026', $document);
    }

    private function createMachinery(string $name, string $createdAt): void
    {
        $machinery = Machinery::create([
            'machinery_name' => $name,
            'model' => 'Test Model',
            'serial_number' => str_replace(' ', '-', $name),
            'price' => '100.00',
            'image_path' => 'test.png',
        ]);

        $machinery->created_at = $createdAt;
        $machinery->save();
    }
}
