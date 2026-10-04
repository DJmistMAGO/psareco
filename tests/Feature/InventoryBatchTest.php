<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Tests\TestCase;
use ZipArchive;

class InventoryBatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_restocking_adds_a_new_expiration_batch_without_replacing_existing_stock(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'status' => 'active',
            'must_change_password' => false,
        ]);
        $this->actingAs($user);
        $firstExpiration = now()->addDays(10)->toDateString();
        $secondExpiration = now()->addDays(60)->toDateString();
        $product = Inventory::create([
            'name' => 'Urea',
            'type' => 'Fertilizer',
            'quantity' => 5,
            'unit' => 'bag',
            'price' => 20,
            'reorder_level' => 2,
            'expiration_date' => $firstExpiration,
        ]);

        $this->post(route('inventory.restock', $product), [
            'quantity' => 7,
            'expiration_date' => $secondExpiration,
        ])->assertRedirect(route('inventory.index'));

        $this->assertDatabaseCount('inventory_batches', 2);
        $batches = $product->fresh()->batches()->orderBy('id')->get();
        $this->assertSame(5, $batches[0]->quantity);
        $this->assertSame($firstExpiration, substr((string) $batches[0]->getRawOriginal('expiration_date'), 0, 10));
        $this->assertSame(7, $batches[1]->quantity);
        $this->assertSame($secondExpiration, substr((string) $batches[1]->getRawOriginal('expiration_date'), 0, 10));
        $product->refresh();
        $this->assertSame(12, (int) $product->quantity);
        $this->assertSame($firstExpiration, substr((string) $product->getRawOriginal('expiration_date'), 0, 10));
    }

    public function test_checkout_consumes_the_earliest_expiring_batch_first(): void
    {
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'officer', 'guard_name' => 'web']);
        /** @var User $officer */
        $officer = User::factory()->create([
            'status' => 'active',
            'must_change_password' => false,
        ]);
        $officer->assignRole('officer');
        $this->actingAs($officer);

        $product = Inventory::create([
            'name' => 'Urea',
            'type' => 'Fertilizer',
            'quantity' => 0,
            'unit' => 'bag',
            'price' => 20,
            'reorder_level' => 2,
        ]);
        $firstBatch = $product->batches()->create([
            'quantity' => 2,
            'expiration_date' => now()->addDays(10)->toDateString(),
        ]);
        $secondBatch = $product->batches()->create([
            'quantity' => 5,
            'expiration_date' => now()->addDays(60)->toDateString(),
        ]);
        $product->syncBatchSummary();

        $this->postJson(route('sales.checkout'), [
            'buyer_name' => 'Test Buyer',
            'items' => [[
                'product_id' => $product->id,
                'quantity' => 3,
            ]],
        ])->assertOk();

        $this->assertDatabaseHas('inventory_batches', ['id' => $firstBatch->id, 'quantity' => 0]);
        $this->assertDatabaseHas('inventory_batches', ['id' => $secondBatch->id, 'quantity' => 4]);
        $this->assertDatabaseHas('inventories', ['id' => $product->id, 'quantity' => 4]);
    }

    public function test_expiring_report_lists_upcoming_batches_even_when_an_older_batch_is_expired(): void
    {
        Role::create(['name' => 'officer', 'guard_name' => 'web']);
        /** @var User $officer */
        $officer = User::factory()->create([
            'status' => 'active',
            'must_change_password' => false,
        ]);
        $officer->assignRole('officer');
        $this->actingAs($officer);

        $product = Inventory::create([
            'name' => 'Urea',
            'type' => 'Fertilizer',
            'quantity' => 0,
            'unit' => 'bag',
            'price' => 20,
            'reorder_level' => 2,
        ]);
        $product->batches()->create([
            'quantity' => 2,
            'expiration_date' => now()->subDay()->toDateString(),
        ]);
        $upcomingBatch = $product->batches()->create([
            'quantity' => 5,
            'expiration_date' => now()->addDays(10)->toDateString(),
        ]);
        $product->syncBatchSummary();

        $this->getJson(route('reports.preview', [
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
            'types' => ['expiring'],
        ]))
            ->assertOk()
            ->assertJsonCount(1, 'expiring_inventory')
            ->assertJsonPath('expiring_inventory.0.quantity', '5.00')
            ->assertJsonPath('expiring_inventory.0.expiration', $upcomingBatch->expiration_date->format('M d, Y'));
    }

    public function test_generated_inventory_and_expiring_reports_list_each_batch_separately(): void
    {
        Role::create(['name' => 'officer', 'guard_name' => 'web']);
        /** @var User $officer */
        $officer = User::factory()->create([
            'status' => 'active',
            'must_change_password' => false,
        ]);
        $officer->assignRole('officer');
        $this->actingAs($officer);

        $product = Inventory::create([
            'name' => 'Batch Test Urea',
            'type' => 'Fertilizer',
            'quantity' => 0,
            'unit' => 'bag',
            'price' => 20,
            'reorder_level' => 2,
        ]);
        $firstExpiration = now()->addDays(10);
        $secondExpiration = now()->addDays(60);
        $product->batches()->create([
            'quantity' => 5,
            'expiration_date' => $firstExpiration->toDateString(),
        ]);
        $product->batches()->create([
            'quantity' => 7,
            'expiration_date' => $secondExpiration->toDateString(),
        ]);
        $product->syncBatchSummary();

        $report = $this->get(route('reports.generate', [
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(90)->toDateString(),
            'types' => ['inventory', 'expiring'],
        ]));
        $report->assertDownload();

        /** @var BinaryFileResponse $download */
        $download = $report->baseResponse;
        $zip = new ZipArchive();
        $this->assertSame(true, $zip->open($download->getFile()->getPathname()));
        $document = $zip->getFromName('word/document.xml');
        $zip->close();

        $this->assertSame(4, substr_count($document, 'Batch Test Urea'));
        $this->assertSame(2, substr_count($document, $firstExpiration->format('M d, Y')));
        $this->assertSame(2, substr_count($document, $secondExpiration->format('M d, Y')));
        $this->assertSame(2, substr_count($document, '>5</w:t>'));
        $this->assertSame(2, substr_count($document, '>7</w:t>'));
    }
}
