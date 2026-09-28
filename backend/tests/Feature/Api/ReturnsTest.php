<?php

namespace Tests\Feature\Api;

use App\Models\Organization;
use App\Models\ReturnDecision;
use App\Models\ReturnModel;
use App\Models\ReturnStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ReturnsTest extends TestCase
{
    use RefreshDatabase;

    private function userInOrganization(): User
    {
        $user = User::factory()->create();
        $organization = Organization::create(['name' => 'Test GmbH']);
        $organization->users()->attach($user->id, ['is_owner' => true]);
        $user->forceFill(['current_organization_id' => $organization->id])->save();
        return $user;
    }

    private function returnFor(User $user, string $number): int
    {
        return DB::table('returns')->insertGetId([
            'organization_id' => $user->current_organization_id,
            'return_number' => $number,
            'status_id' => ReturnStatus::where('code', 'created')->firstOrFail()->id,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function test_user_can_create_return_with_customer_and_item_inside_current_organization(): void
    {
        $user = $this->userInOrganization();

        $this->actingAs($user)->postJson('/api/returns/store', [
            'return_number' => 'RET-1',
            'customer' => ['name' => 'Ada Customer', 'email' => 'ada@example.com'],
            'items' => [['line_no' => 1, 'item_name' => 'Shoes', 'quantity' => 2]],
        ])->assertCreated()->assertJsonPath('data.return_number', 'RET-1')
            ->assertJsonPath('data.organization_id', $user->current_organization_id)
            ->assertJsonPath('data.customer.name', 'Ada Customer')
            ->assertJsonPath('data.items.0.item_name', 'Shoes');

        $this->assertDatabaseHas('customers', [
            'organization_id' => $user->current_organization_id, 'email' => 'ada@example.com',
        ]);
        $this->assertDatabaseHas('return_items', ['item_name' => 'Shoes', 'quantity' => 2]);
    }

    public function test_user_can_list_only_own_organization_returns(): void
    {
        $user = $this->userInOrganization();
        $ownId = $this->returnFor($user, 'OWN-1');
        $other = $this->userInOrganization();
        $foreignId = $this->returnFor($other, 'FOREIGN-1');

        $response = $this->actingAs($user)->postJson('/api/returns/list', []);

        $response->assertOk()->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $ownId);
        $this->assertNotEquals($foreignId, $response->json('data.0.id'));
    }

    public function test_user_cannot_view_or_update_return_from_another_organization(): void
    {
        $user = $this->userInOrganization();
        $other = $this->userInOrganization();
        $foreignId = $this->returnFor($other, 'FOREIGN-1');

        $this->actingAs($user)->getJson("/api/returns/$foreignId")->assertNotFound();
        $this->patchJson("/api/returns/$foreignId", ['reason' => 'Changed'])
            ->assertNotFound();
        $this->assertDatabaseHas('returns', ['id' => $foreignId, 'reason' => null]);
    }

    public function test_user_can_update_return_status_and_change_creates_event(): void
    {
        $user = $this->userInOrganization();
        $returnId = $this->returnFor($user, 'OWN-1');
        $statusId = ReturnStatus::where('code', 'in_review')->firstOrFail()->id;

        $this->actingAs($user)->patchJson("/api/returns/$returnId", [
            'status_id' => $statusId,
        ])->assertCreated()->assertJsonPath('data.status_id', $statusId);

        $this->assertDatabaseHas('return_events', [
            'organization_id' => $user->current_organization_id,
            'return_id' => $returnId, 'field' => 'return.status_id',
            'ref_type' => 'status', 'ref_id' => $statusId,
        ]);
    }

    public function test_user_can_set_return_decision_and_change_creates_event(): void
    {
        $user = $this->userInOrganization();
        $returnId = $this->returnFor($user, 'OWN-1');
        $decisionId = ReturnDecision::where('code', 'refund_full')->firstOrFail()->id;
        $approvedId = ReturnStatus::where('code', 'approved')->firstOrFail()->id;

        $this->actingAs($user)->patchJson("/api/returns/$returnId/decision", [
            'decision_id' => $decisionId,
        ])->assertCreated()->assertJsonPath('data.decision_id', $decisionId)
            ->assertJsonPath('data.status_id', $approvedId);

        $this->assertDatabaseHas('return_events', [
            'return_id' => $returnId, 'field' => 'return.decision_id',
            'ref_type' => 'decision', 'ref_id' => $decisionId,
        ]);
        $this->assertDatabaseHas('return_events', [
            'return_id' => $returnId, 'field' => 'return.status_id', 'ref_id' => $approvedId,
        ]);
    }
}
