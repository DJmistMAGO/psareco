<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfilePasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_password_cannot_match_the_temporary_password(): void
    {
        $temporaryPassword = 'temporary-password';
        $user = User::factory()->create([
            'password' => $temporaryPassword,
            'status' => 'active',
            'must_change_password' => true,
        ]);

        $this->actingAs($user)
            ->from('/')
            ->post(route('profile.password.update'), [
                'current_password' => $temporaryPassword,
                'password' => $temporaryPassword,
                'password_confirmation' => $temporaryPassword,
            ])
            ->assertSessionHasErrors('password');

        $this->assertTrue(Hash::check($temporaryPassword, $user->fresh()->password));
        $this->assertTrue($user->fresh()->must_change_password);
    }
}
