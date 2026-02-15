<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        $now = now();

        // RETURN STATUSES (organization_id = NULL => system/global)
        // kind: 1=initial, 2=normal, 3=terminal
        DB::table('return_statuses')->insert([
            [
                'organization_id' => null,
                'code' => 'created',
                'name' => 'Erfasst',
                'kind' => 1,
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'waiting_item',
                'name' => 'Warten auf Rücksendung',
                'kind' => 2,
                'sort_order' => 20,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'in_review',
                'name' => 'In Prüfung',
                'kind' => 2,
                'sort_order' => 30,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'approved',
                'name' => 'Freigegeben',
                'kind' => 2,
                'sort_order' => 40,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'rejected',
                'name' => 'Abgelehnt',
                'kind' => 9,
                'sort_order' => 90,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'closed',
                'name' => 'Abgeschlossen',
                'kind' => 9,
                'sort_order' => 100,
                'is_active' => true,
            ],
        ]);

        // REFUND STATUSES
        DB::table('refund_statuses')->insert([
            [
                'code' => 'not_required',
                'name' => 'Keine Erstattung erforderlich',
                'is_counted' => true,
                'sort_order' => 10,
            ],
            [
                'code' => 'pending',
                'name' => 'Ausstehend',
                'is_counted' => true,
                'sort_order' => 20,
            ],
            [
                'code' => 'processing',
                'name' => 'In Bearbeitung',
                'is_counted' => true,
                'sort_order' => 30,
            ],
            [
                'code' => 'refunded',
                'name' => 'Erstattet',
                'is_counted' => true,
                'sort_order' => 90,
            ],
            [
                'code' => 'failed',
                'name' => 'Fehlgeschlagen',
                'is_counted' => false,
                'sort_order' => 100,
            ],
        ]);

        // SHIPMENT STATUSES
        DB::table('shipment_statuses')->insert([
            [
                'code' => 'created',
                'name' => 'Erfasst',
                'is_terminal' => false,
                'sort_order' => 10,
            ],
            [
                'code' => 'shipped',
                'name' => 'Versendet',
                'is_terminal' => false,
                'sort_order' => 20,
            ],
            [
                'code' => 'in_transit',
                'name' => 'Unterwegs',
                'is_terminal' => false,
                'sort_order' => 30,
            ],
            [
                'code' => 'delivered',
                'name' => 'Zugestellt',
                'is_terminal' => true,
                'sort_order' => 90,
            ],
            [
                'code' => 'returned',
                'name' => 'Retoure an Absender',
                'is_terminal' => true,
                'sort_order' => 100,
            ],
            [
                'code' => 'cancelled',
                'name' => 'Storniert',
                'is_terminal' => true,
                'sort_order' => 110,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete only system rows we inserted (safe rollback).
        DB::table('return_statuses')
            ->whereNull('organization_id')
            ->whereIn('code', ['created','waiting_item','in_review','approved','rejected','closed'])
            ->delete();

        DB::table('refund_statuses')
            ->whereIn('code', ['not_required','pending','processing','refunded','failed'])
            ->delete();

        DB::table('shipment_statuses')
            ->whereIn('code', ['created','shipped','in_transit','delivered','returned','cancelled'])
            ->delete();
    }
};
