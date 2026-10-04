<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_user_rejects_invalid_text_and_contact_characters(): void
    {
        $this->signInAsAdmin();

        $this->from(route('user-management.index'))->post(route('user-management.adduser'), [
            '_form' => 'add_user',
            'name' => 'Maria2 Santos',
            'email' => 'maria@example.com',
            'password' => 'password123',
            'contact_number' => 'call-me',
            'position' => 'Farm@Staff',
            'role' => 'officer',
        ])->assertSessionHasErrors(['name', 'contact_number', 'position']);

        $this->get(route('user-management.index'))
            ->assertSee('open: true')
            ->assertSee('Maria2 Santos');

        $this->assertDatabaseMissing('users', ['email' => 'maria@example.com']);
    }

    public function test_update_user_rejects_invalid_text_characters(): void
    {
        $this->signInAsAdmin();
        Role::create(['name' => 'farmer', 'guard_name' => 'web']);
        $user = User::factory()->create([
            'status' => 'active',
            'must_change_password' => false,
        ]);
        $user->assignRole('farmer');

        $this->from(route('user-management.index'))
            ->put(route('user-management.updateUser', $user), [
                '_form' => 'edit_user',
                '_user_id' => $user->id,
                'name' => 'Farmer7 Name',
                'email' => $user->email,
                'contact_number' => '0917-123-4567',
                'position' => 'Farm@Staff',
                'role' => 'farmer',
                'status' => 'active',
            ])->assertSessionHasErrors(['name', 'position']);

        $this->get(route('user-management.index'))
            ->assertSee('open: true')
            ->assertSee('Farmer7 Name');

        $this->assertSame($user->name, $user->fresh()->name);
    }

    private function signInAsAdmin(): void
    {
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        /** @var User $admin */
        $admin = User::factory()->create([
            'status' => 'active',
            'must_change_password' => false,
        ]);
        $admin->assignRole('admin');
        $this->actingAs($admin);
    }
}
