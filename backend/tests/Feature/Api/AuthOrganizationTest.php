<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthOrganizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config()->set('sanctum.stateful', ['localhost:5173']);
        config()->set('sanctum.middleware.validate_csrf_token', null);
        $this->withHeader('Origin', 'http://localhost:5173');
    }

    public function test_user_can_register_and_legal_acceptance_is_recorded(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'legal_acceptances' => [
                ['document_key' => 'terms', 'document_version' => '1', 'action' => 'accepted'],
                ['document_key' => 'privacy', 'document_version' => '2', 'action' => 'acknowledged'],
            ],
        ]);

        $this->assertSame(201, $response->status(), $response->content());
        $response->assertJsonPath('user.email', 'test@example.com');
        $user = User::where('email', 'test@example.com')->firstOrFail();
        $this->assertDatabaseHas('legal_acceptances', [
            'user_id' => $user->id, 'document_key' => 'terms', 'document_version' => '1',
            'action' => 'accepted', 'context' => 'registration',
        ]);
        $this->assertDatabaseHas('legal_acceptances', [
            'user_id' => $user->id, 'document_key' => 'privacy', 'document_version' => '2',
            'action' => 'acknowledged', 'context' => 'registration',
        ]);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertSame(200, $response->status(), $response->content());
        $response->assertJsonPath('user.id', $user->id);

        $this->getJson('/api/auth/me')->assertOk()->assertJsonPath('user.id', $user->id);
    }

    public function test_user_can_create_organization_and_owner_accepts_required_document(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/organization', [
            'name' => 'Test GmbH',
            'legal_acceptances' => [[
                'document_key' => 'avv', 'document_version' => '3',
                'document_hash' => 'test-hash', 'action' => 'contract_concluded',
            ]],
        ]);

        $response->assertCreated()->assertJsonPath('data.name', 'Test GmbH')
            ->assertJsonPath('data.avv_acceptance.document_version', '3');
        $organizationId = $response->json('data.id');
        $this->assertDatabaseHas('organization_user', [
            'organization_id' => $organizationId, 'user_id' => $user->id, 'is_owner' => true,
        ]);
        $this->assertSame($organizationId, $user->fresh()->current_organization_id);
        $this->assertDatabaseHas('legal_acceptances', [
            'organization_id' => $organizationId, 'user_id' => $user->id,
            'document_key' => 'avv', 'document_version' => '3', 'document_hash' => 'test-hash',
            'action' => 'contract_concluded', 'context' => 'organization_creation',
        ]);
    }

    public function test_organization_requires_avv_acceptance(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/api/organization', ['name' => 'Test GmbH'])
            ->assertUnprocessable()->assertJsonValidationErrors('legal_acceptances');
        $this->assertDatabaseCount('organizations', 0);
    }
}
