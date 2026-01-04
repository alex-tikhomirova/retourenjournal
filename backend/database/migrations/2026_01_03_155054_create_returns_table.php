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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('return_number');

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('status_id')
                ->references('id')
                ->on('return_statuses')
                ->restrictOnDelete();

            // smallint FK to return_decisions (nullable)
            $table->unsignedSmallInteger('decision_id')->nullable();
            $table->foreign('decision_id')
                ->references('id')
                ->on('return_decisions')
                ->nullOnDelete();

            $table->string('order_reference');

            $table->text('reason')->nullable();
            $table->text('internal_note')->nullable();
            $table->foreignId('created_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();

            $table->unique(['organization_id', 'return_number']);

            $table->index(['organization_id', 'status_id']);
            $table->index(['organization_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
