<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('return_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('return_id')
                ->constrained('returns')
                ->cascadeOnDelete();

            $table->foreignId('created_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // 0 = created, 1 = updated
            $table->unsignedSmallInteger('action');

            // e.g. status_id, decision_id, shipment, refund
            $table->string('field')->nullable();

            // Typed references (restricted in application code to:
            // return_status | return_decision | return_shipment | return_refund)
            $table->string('ref_type')->nullable();
            $table->unsignedBigInteger('ref_id')->nullable();
            // Extra payload
            $table->text('value')->nullable();

            // We want event time to be explicit and index-friendly
            $table->timestamp('created_at')->useCurrent();

            $table->index(['organization_id', 'return_id', 'created_at']);
            $table->index(['organization_id', 'ref_type', 'ref_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_events');
    }
};
