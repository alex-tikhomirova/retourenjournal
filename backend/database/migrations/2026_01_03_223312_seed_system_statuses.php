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
                'color' => '#FAD7E0',
                'name' => 'Erfasst',
                'description' => 'Rückgabe erstellt, noch nicht bearbeitet',
                'kind' => 1,
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'waiting_item',
                'color' => '#FCECC7',
                'name' => 'Warten auf Rücksendung',
                'description' => 'Kunde muss Ware zurücksenden',
                'kind' => 2,
                'sort_order' => 20,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'in_review',
                'color' => '#D9ECFF',
                'name' => 'In Prüfung',
                'description' => 'Ware wird geprüft und bewertet',
                'kind' => 2,
                'sort_order' => 30,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'approved',
                'color' => '#DDF4E4',
                'name' => 'Freigegeben',
                'description' => 'Rückgabe akzeptiert, nächste Schritte folgen',
                'kind' => 2,
                'sort_order' => 40,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'rejected',
                'color' => '#E9E2F8',
                'name' => 'Abgelehnt',
                'description' => 'Rückgabe nicht akzeptiert, abgeschlossen',
                'kind' => 9,
                'sort_order' => 90,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'closed',
                'color' => '#E9EDF2',
                'name' => 'Abgeschlossen',
                'description' => 'Vorgang vollständig abgeschlossen und erledigt',
                'kind' => 9,
                'sort_order' => 100,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'cancelled',
                'color' => '#E9EDF2',
                'name' => 'Storniert',
                'description' => 'Rückgabe vorzeitig storniert',
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
                'description' => 'Keine Rückzahlung für diesen Fall nötig',
                'is_counted' => true,
                'sort_order' => 10,
            ],
            [
                'code' => 'pending',
                'name' => 'Ausstehend',
                'description' => 'Erstattung geplant, noch nicht gestartet',
                'is_counted' => true,
                'sort_order' => 20,
            ],
            [
                'code' => 'processing',
                'name' => 'In Bearbeitung',
                'description' => 'Zahlung wird aktuell verarbeitet',
                'is_counted' => true,
                'sort_order' => 30,
            ],
            [
                'code' => 'refunded',
                'name' => 'Erstattet',
                'description' => 'Betrag erfolgreich an Kunden überwiesen',
                'is_counted' => true,
                'sort_order' => 90,
            ],
            [
                'code' => 'failed',
                'name' => 'Fehlgeschlagen',
                'description' => 'Erstattung fehlgeschlagen, erneuter Versuch nötig',
                'is_counted' => false,
                'sort_order' => 100,
            ],
        ]);

        // SHIPMENT STATUSES
        DB::table('shipment_statuses')->insert([
            [
                'code' => 'created',
                'name' => 'Erfasst',
                'description' => 'Versandauftrag erstellt, noch nicht verschickt',
                'is_terminal' => false,
                'sort_order' => 10,
            ],
            [
                'code' => 'shipped',
                'name' => 'Versendet',
                'description' => 'Paket wurde an Transportdienst übergeben',
                'is_terminal' => false,
                'sort_order' => 20,
            ],
            [
                'code' => 'in_transit',
                'name' => 'Unterwegs',
                'description' => 'Sendung befindet sich im Transport',
                'is_terminal' => false,
                'sort_order' => 30,
            ],
            [
                'code' => 'delivered',
                'name' => 'Zugestellt',
                'description' => 'Sendung erfolgreich beim Empfänger angekommen',
                'is_terminal' => true,
                'sort_order' => 90,
            ],
            [
                'code' => 'returned',
                'name' => 'Retoure an Absender',
                'description' => 'Paket wird zurück an Absender geschickt',
                'is_terminal' => true,
                'sort_order' => 100,
            ],
            [
                'code' => 'cancelled',
                'name' => 'Storniert',
                'description' => 'Versandauftrag wurde abgebrochen oder gelöscht',
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
