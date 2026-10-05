<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class InventoryUnitValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_rejects_a_numeric_only_unit(): void
    {
        $this->actingAsOfficer();

        $this->post(route('inventory.addProduct'), [
            'name' => 'Urea',
            'type' => 'Fertilizer',
            'quantity' => 5,
            'unit' => '123',
            'price' => 20,
            'reorder_level' => 2,
        ])->assertSessionHasErrors(['unit']);

        $this->assertDatabaseCount('inventories', 0);
    }

    public function test_update_rejects_a_numeric_only_unit(): void
    {
        $this->actingAsOfficer();
        $inventory = Inventory::create([
            'name' => 'Urea',
            'type' => 'Fertilizer',
            'quantity' => 5,
            'unit' => 'bag',
            'price' => 20,
            'reorder_level' => 2,
        ]);

        $this->put(route('inventory.updateProduct', $inventory), [
            'name' => 'Urea',
            'type' => 'Fertilizer',
            'unit' => '123.5',
            'price' => 20,
            'reorder_level' => 2,
        ])->assertSessionHasErrors(['unit']);

        $this->assertDatabaseHas('inventories', [
            'id' => $inventory->id,
            'unit' => 'bag',
        ]);
    }

    public function test_create_rejects_numbers_after_unit_text(): void
    {
        $this->actingAsOfficer();

        $this->post(route('inventory.addProduct'), [
            'name' => 'Urea',
            'type' => 'Fertilizer',
            'quantity' => 5,
            'unit' => 'bags5',
            'price' => 20,
            'reorder_level' => 2,
        ])->assertSessionHasErrors(['unit']);

        $this->assertDatabaseCount('inventories', 0);
    }

    public function test_create_accepts_a_descriptive_unit_label(): void
    {
        $this->actingAsOfficer();
        Storage::fake('public');

        $this->post(route('inventory.addProduct'), [
            'name' => 'Urea',
            'type' => 'Fertilizer',
            'quantity' => 5,
            'unit' => 'bottle',
            'price' => 20,
            'reorder_level' => 2,
            'image_path' => UploadedFile::fake()->image('urea.png'),
        ])->assertRedirect(route('inventory.index'));

        $this->assertDatabaseHas('inventories', ['unit' => 'bottle']);
    }

    private function actingAsOfficer(): void
    {
        $role = Role::create(['name' => 'officer', 'guard_name' => 'web']);
        /** @var User $officer */
        $officer = User::factory()->create([
            'status' => 'active',
            'must_change_password' => false,
        ]);
        $officer->assignRole($role);

        $this->actingAs($officer);
    }
}
