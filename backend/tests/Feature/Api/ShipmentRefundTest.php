<?php

namespace Tests\Feature\Api;

use App\Models\Organization;
use App\Models\RefundStatus;
use App\Models\ReturnStatus;
use App\Models\ShipmentStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ShipmentRefundTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Number allocation uses PostgreSQL advisory locks; SQLite tests only
        // need these functions to leave the sequence query executable.
        $pdo = DB::connection()->getPdo();
        if ($pdo->getAttribute(\PDO::ATTR_DRIVER_NAME) === 'sqlite') {
            $pdo->sqliteCreateFunction('hashtextextended', fn () => 1, 2);
            $pdo->sqliteCreateFunction('pg_advisory_xact_lock', fn () => 1, 1);
        }
    }

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

    public function test_user_can_create_and_update_shipment_and_changes_create_events(): void
    {
        $user = $this->userInOrganization();
        $returnId = $this->returnFor($user, 'OWN-1');

        $created = $this->actingAs($user)->postJson('/api/shipments', [
            'return_id' => $returnId, 'direction' => 1, 'payer' => 2,
            'carrier' => 'DHL', 'tracking_number' => 'TRACK-1',
        ]);
        $created->assertCreated()->assertJsonPath('data.return_id', $returnId)
            ->assertJsonPath('data.organization_id', $user->current_organization_id);
        $shipmentId = $created->json('data.id');
        $this->assertDatabaseHas('return_events', [
            'return_id' => $returnId, 'field' => 'shipment.shipment_number',
            'ref_type' => 'shipment', 'ref_id' => 1,
        ]);

        $shippedId = ShipmentStatus::where('code', 'shipped')->firstOrFail()->id;
        $this->patchJson("/api/shipments/$shipmentId", [
            'status_id' => $shippedId, 'tracking_number' => 'TRACK-2',
        ])->assertCreated()->assertJsonPath('data.tracking_number', 'TRACK-2')
            ->assertJsonPath('data.status_id', $shippedId);
        $this->assertDatabaseHas('return_events', [
            'return_id' => $returnId, 'field' => 'shipment.status_id',
            'ref_type' => 'shipmentstatus', 'ref_id' => $shippedId,
        ]);
        $this->assertDatabaseHas('return_events', [
            'return_id' => $returnId, 'field' => 'shipment.tracking_number', 'value' => 'TRACK-2',
        ]);
    }

    public function test_user_cannot_create_or_update_foreign_shipment(): void
    {
        $user = $this->userInOrganization();
        $other = $this->userInOrganization();
        $foreignReturnId = $this->returnFor($other, 'FOREIGN-1');
        $shipmentId = DB::table('return_shipments')->insertGetId([
            'organization_id' => $other->current_organization_id,
            'return_id' => $foreignReturnId, 'shipment_number' => 1,
            'direction' => 1, 'payer' => 2, 'currency' => 'EUR',
            'status_id' => ShipmentStatus::where('code', 'created')->firstOrFail()->id,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($user)->postJson('/api/shipments', [
            'return_id' => $foreignReturnId, 'direction' => 1, 'payer' => 2,
        ])->assertNotFound();
        $this->patchJson("/api/shipments/$shipmentId", [
            'status_id' => ShipmentStatus::where('code', 'shipped')->firstOrFail()->id,
        ])->assertNotFound();
        $this->assertDatabaseCount('return_shipments', 1);
        $this->assertDatabaseHas('return_shipments', [
            'id' => $shipmentId,
            'status_id' => ShipmentStatus::where('code', 'created')->firstOrFail()->id,
        ]);
    }

    public function test_user_can_create_and_update_refund_and_changes_create_events(): void
    {
        $user = $this->userInOrganization();
        $returnId = $this->returnFor($user, 'OWN-1');

        $created = $this->actingAs($user)->postJson('/api/refunds', [
            'return_id' => $returnId, 'amount' => 12.50, 'reference' => 'ORDER-1',
        ]);
        $created->assertCreated()->assertJsonPath('data.return_id', $returnId)
            ->assertJsonPath('data.amount_cents', 1250)
            ->assertJsonPath('data.organization_id', $user->current_organization_id);
        $refundId = $created->json('data.id');
        $this->assertDatabaseHas('return_events', [
            'return_id' => $returnId, 'field' => 'refund.refund_number',
            'ref_type' => 'refund', 'ref_id' => 1,
        ]);

        $refundedId = RefundStatus::where('code', 'refunded')->firstOrFail()->id;
        $this->patchJson("/api/refunds/$refundId", [
            'status_id' => $refundedId, 'reference' => 'PAYMENT-2',
        ])->assertCreated()->assertJsonPath('data.status_id', $refundedId)
            ->assertJsonPath('data.reference', 'PAYMENT-2');
        $this->assertDatabaseHas('return_events', [
            'return_id' => $returnId, 'field' => 'refund.status_id',
            'ref_type' => 'refundstatus', 'ref_id' => $refundedId,
        ]);
        $this->assertDatabaseHas('return_events', [
            'return_id' => $returnId, 'field' => 'refund.reference', 'value' => 'PAYMENT-2',
        ]);
    }

    public function test_user_cannot_create_or_update_foreign_refund(): void
    {
        $user = $this->userInOrganization();
        $other = $this->userInOrganization();
        $foreignReturnId = $this->returnFor($other, 'FOREIGN-1');
        $refundId = DB::table('return_refunds')->insertGetId([
            'organization_id' => $other->current_organization_id,
            'return_id' => $foreignReturnId, 'refund_number' => 1,
            'amount_cents' => 1250, 'currency' => 'EUR',
            'status_id' => RefundStatus::where('code', 'pending')->firstOrFail()->id,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($user)->postJson('/api/refunds', [
            'return_id' => $foreignReturnId, 'amount' => 12.50,
        ])->assertNotFound();
        $this->patchJson("/api/refunds/$refundId", [
            'status_id' => RefundStatus::where('code', 'refunded')->firstOrFail()->id,
        ])->assertNotFound();
        $this->assertDatabaseCount('return_refunds', 1);
        $this->assertDatabaseHas('return_refunds', [
            'id' => $refundId,
            'status_id' => RefundStatus::where('code', 'pending')->firstOrFail()->id,
        ]);
    }
}
