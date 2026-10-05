<?php

namespace Tests\Feature;

use App\Models\Machinery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MachineryImageUploadValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_rejects_tiff_image_uploads(): void
    {
        $this->actingAsOfficer();

        $this->post(route('machinery.store'), [
            'machinery_name' => 'Test Tractor',
            'model' => 'TX-1',
            'serial_number' => 'TX-001',
            'price' => '100.00',
            'image_path' => UploadedFile::fake()->image('tractor.TIFF'),
            'status' => 'Available',
        ])
            ->assertSessionHasErrors(['image_path'])
            ->assertSessionHas('errors', fn($errors) => str_contains(
                $errors->first('image_path'),
                'Please upload a valid JPG, JPEG, or PNG image.'
            ));

        $this->assertDatabaseCount('machineries', 0);
    }

    public function test_update_rejects_tiff_image_uploads(): void
    {
        $this->actingAsOfficer();
        $machinery = Machinery::create([
            'machinery_name' => 'Test Tractor',
            'model' => 'TX-1',
            'serial_number' => 'TX-001',
            'price' => '100.00',
            'image_path' => 'machinery/original.png',
            'status' => 'Available',
        ]);

        $this->put(route('machinery.update', $machinery), [
            'machinery_name' => 'Test Tractor',
            'model' => 'TX-1',
            'serial_number' => 'TX-001',
            'price' => '100.00',
            'image_path' => UploadedFile::fake()->image('tractor.TIFF'),
            'status' => 'Available',
        ])->assertSessionHasErrors(['image_path']);

        $this->assertDatabaseHas('machineries', [
            'id' => $machinery->id,
            'image_path' => 'machinery/original.png',
        ]);
    }

    public function test_store_rejects_html_syntax_in_machinery_name(): void
    {
        $this->actingAsOfficer();

        $this->post(route('machinery.store'), [
            'machinery_name' => '<script>alert(1)</script>',
            'model' => 'TX-1',
            'serial_number' => 'TX-001',
            'price' => '100.00',
            'image_path' => UploadedFile::fake()->image('tractor.png'),
            'status' => 'Available',
        ])->assertSessionHasErrors(['machinery_name']);

        $this->assertDatabaseCount('machineries', 0);
    }

    public function test_update_rejects_html_syntax_in_machinery_name(): void
    {
        $this->actingAsOfficer();
        $machinery = Machinery::create([
            'machinery_name' => 'Test Tractor',
            'model' => 'TX-1',
            'serial_number' => 'TX-001',
            'price' => '100.00',
            'image_path' => 'machinery/original.png',
            'status' => 'Available',
        ]);

        $this->put(route('machinery.update', $machinery), [
            'machinery_name' => 'Tractor <b>Plus</b>',
            'model' => 'TX-1',
            'serial_number' => 'TX-001',
            'price' => '100.00',
            'status' => 'Available',
        ])->assertSessionHasErrors(['machinery_name']);

        $this->assertDatabaseHas('machineries', [
            'id' => $machinery->id,
            'machinery_name' => 'Test Tractor',
        ]);
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
