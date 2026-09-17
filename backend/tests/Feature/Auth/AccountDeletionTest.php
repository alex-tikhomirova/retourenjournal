<?php

namespace Tests\Feature\Auth;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_delete_account_and_organization(): void
    {
        $user = User::factory()->create();
        $organization = Organization::create(['name' => 'Test GmbH']);
        $organization->users()->attach($user->id, ['is_owner' => true]);
        $user->forceFill(['current_organization_id' => $organization->id])->save();

        $response = $this
            ->actingAs($user)
            ->deleteJson('/api/auth/profile', ['password' => 'password']);

        $response->assertOk()->assertJson(['ok' => true]);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('organizations', ['id' => $organization->id]);
    }

    public function test_password_is_required_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->deleteJson('/api/auth/profile', ['password' => 'wrong-password']);

        $response->assertUnprocessable()->assertJsonValidationErrors('password');
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_owner_must_transfer_ownership_or_remove_members_before_deleting_account(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $organization = Organization::create(['name' => 'Shared GmbH']);
        $organization->users()->attach([
            $user->id => ['is_owner' => true],
            $otherUser->id => ['is_owner' => false],
        ]);
        $user->forceFill(['current_organization_id' => $organization->id])->save();
        $otherUser->forceFill(['current_organization_id' => $organization->id])->save();

        $this
            ->actingAs($user)
            ->deleteJson('/api/auth/profile', ['password' => 'password'])
            ->assertConflict()
            ->assertJsonPath(
                'message',
                'Die Organisation hat weitere Mitglieder. Übertragen Sie die Inhaberschaft oder entfernen Sie die Mitglieder, bevor Sie Ihr Konto löschen.'
            );

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertDatabaseHas('users', ['id' => $otherUser->id]);
        $this->assertDatabaseHas('organizations', ['id' => $organization->id]);
        $this->assertDatabaseHas('users', [
            'id' => $otherUser->id,
            'current_organization_id' => $organization->id,
        ]);
    }

    public function test_non_owner_deletes_only_their_account(): void
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();
        $organization = Organization::create(['name' => 'Shared GmbH']);
        $organization->users()->attach([
            $owner->id => ['is_owner' => true],
            $user->id => ['is_owner' => false],
        ]);
        $owner->forceFill(['current_organization_id' => $organization->id])->save();
        $user->forceFill(['current_organization_id' => $organization->id])->save();

        $this
            ->actingAs($user)
            ->deleteJson('/api/auth/profile', ['password' => 'password'])
            ->assertOk();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseHas('users', ['id' => $owner->id]);
        $this->assertDatabaseHas('organizations', ['id' => $organization->id]);
    }
}
