<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('return_decisions', function (Blueprint $table) {
            $table->smallIncrements('id');

            // NULL = global default decision
            // NOT NULL = organization-specific custom decision
            $table->foreignId('organization_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('code', 50);
            $table->string('name', 255);
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->string('outcome', 20); // approve|reject

            $table->boolean('requires_inbound_item')->default(false);
            $table->boolean('requires_refund')->default(false);
            $table->boolean('requires_outbound_shipment')->default(false);

            $table->boolean('is_active')->default(true);


            $table->unique(['organization_id', 'code']);
            $table->index(['organization_id', 'is_active', 'sort_order']);
            $table->index(['organization_id', 'outcome', 'sort_order']);
        });

        // Seed GLOBAL (organization_id = null)
        $now = now();

        DB::table('return_decisions')->insert([
            [
                'organization_id' => null,
                'code' => 'refund_full',
                'name' => 'Vollerstattung (Geld zurück)',
                'sort_order' => 10,
                'outcome' => 'approve',
                'requires_inbound_item' => true,
                'requires_refund' => true,
                'requires_outbound_shipment' => false,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'refund_partial',
                'name' => 'Teilerstattung',
                'sort_order' => 20,
                'outcome' => 'approve',
                'requires_inbound_item' => true,
                'requires_refund' => true,
                'requires_outbound_shipment' => false,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'replacement',
                'name' => 'Ersatzlieferung (Umtausch)',
                'sort_order' => 30,
                'outcome' => 'approve',
                'requires_inbound_item' => true,
                'requires_refund' => false,
                'requires_outbound_shipment' => true,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'refund_no_return',
                'name' => 'Erstattung ohne Rücksendung',
                'sort_order' => 40,
                'outcome' => 'approve',
                'requires_inbound_item' => false,
                'requires_refund' => true,
                'requires_outbound_shipment' => false,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'reject_out_of_policy',
                'name' => 'Abgelehnt (außerhalb der Rückgabebedingungen)',
                'sort_order' => 90,
                'outcome' => 'reject',
                'requires_inbound_item' => false,
                'requires_refund' => false,
                'requires_outbound_shipment' => false,
                'is_active' => true,
            ],
            [
                'organization_id' => null,
                'code' => 'reject_condition',
                'name' => 'Abgelehnt (Ware unvollständig/beschädigt)',
                'sort_order' => 100,
                'outcome' => 'reject',
                'requires_inbound_item' => true,
                'requires_refund' => false,
                'requires_outbound_shipment' => false,
                'is_active' => true,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_decisions');
    }
};
