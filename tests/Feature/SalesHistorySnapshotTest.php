<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\Sales;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SalesHistorySnapshotTest extends TestCase
{
    use RefreshDatabase;

    public function test_sales_history_and_report_keep_product_details_after_inventory_is_deleted(): void
    {
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $officerRole = Role::create(['name' => 'officer', 'guard_name' => 'web']);
        /** @var User $officer */
        $officer = User::factory()->create([
            'status' => 'active',
            'must_change_password' => false,
        ]);
        $officer->assignRole($officerRole);
        $this->actingAs($officer);

        $product = Inventory::create([
            'name' => 'Archived Product',
            'type' => 'Fertilizer',
            'quantity' => 5,
            'unit' => 'bag',
            'price' => 10.50,
            'reorder_level' => 1,
        ]);

        $date = now()->toDateString();

        $this->postJson(route('sales.checkout'), [
            'buyer_name' => 'Test Buyer',
            'items' => [[
                'product_id' => $product->id,
                'quantity' => 2,
            ]],
        ])->assertOk();

        $product->forceDelete();

        $this->assertDatabaseHas('sales', [
            'product_id' => null,
            'product_name' => 'Archived Product',
            'product_unit' => 'bag',
        ]);

        $this->get(route('sales.index'))
            ->assertOk()
            ->assertSee('Archived Product')
            ->assertSee('bag');

        $this->getJson(route('reports.preview', [
            'start_date' => $date,
            'end_date' => $date,
            'types' => ['sales'],
        ]))
            ->assertOk()
            ->assertJsonPath('sales.0.product_name', 'Archived Product');

        $this->assertSame('Archived Product', Sales::firstOrFail()->product_name);
    }
}
