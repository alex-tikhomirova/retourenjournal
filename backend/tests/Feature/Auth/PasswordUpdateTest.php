<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_can_be_updated_through_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patchJson('/api/auth/profile', [
            'name' => $user->name,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertOk();

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    public function test_password_confirmation_is_required_when_changing_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patchJson('/api/auth/profile', [
            'name' => $user->name,
            'password' => 'new-password',
            'password_confirmation' => 'wrong-password',
        ])->assertUnprocessable()->assertJsonValidationErrors('password');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
