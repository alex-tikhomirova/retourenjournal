<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_get_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->getJson('/api/auth/me')
            ->assertOk()->assertJsonPath('user.id', $user->id);
    }

    public function test_profile_name_can_be_updated_without_changing_email_or_verification(): void
    {
        $user = User::factory()->create();
        $verifiedAt = $user->email_verified_at->toDateTimeString();

        $this->actingAs($user)->patchJson('/api/auth/profile', [
            'name' => 'Test User',
        ])->assertOk()->assertJsonPath('user.name', 'Test User');

        $user->refresh();
        $this->assertSame('Test User', $user->name);
        $this->assertSame($verifiedAt, $user->email_verified_at->toDateTimeString());
    }

    public function test_profile_email_cannot_be_changed(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->patchJson('/api/auth/profile', [
            'name' => $user->name,
            'email' => 'other@example.com',
        ])->assertOk()->assertJsonPath('user.email', $user->email);

        $this->assertSame($user->email, $user->fresh()->email);
    }
}
