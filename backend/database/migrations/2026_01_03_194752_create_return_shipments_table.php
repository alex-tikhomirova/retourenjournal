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

        Schema::create('return_shipments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('return_id')
                ->constrained('returns')
                ->cascadeOnDelete();

            // Enums stored as smallint (no lookup tables, no joins)
            // direction: 1 = to_merchant, 2 = to_customer
            $table->unsignedSmallInteger('direction');

            // payer: 1 = customer, 2 = merchant, 3 = plarform, 4 = shared, 5 = unknown
            $table->unsignedSmallInteger('payer');

            $table->integer('cost_cents')->nullable();
            $table->char('currency', 3)->default('EUR');

            // Shipment status as reference table
            $table->foreignId('status_id')
                ->constrained('shipment_statuses')
                ->restrictOnDelete();

            $table->string('carrier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('label_ref')->nullable();

            // audit
            $table->foreignId('created_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['organization_id', 'return_id', 'created_at']);
            $table->index(['organization_id', 'direction', 'created_at']);
            $table->index(['organization_id', 'tracking_number']);
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_shipments');
    }
};
