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
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('return_id')
                ->constrained('returns')
                ->cascadeOnDelete();

            // Filled by application logic (1..N). No UNIQUE index by design.
            $table->unsignedSmallInteger('line_no');

            $table->string('sku')->nullable();
            $table->string('item_name');
            $table->unsignedInteger('quantity')->default(1);

            $table->integer('unit_price_cents')->nullable();
            $table->char('currency', 3)->default('EUR');

            $table->index(['organization_id', 'return_id']);
            $table->index(['organization_id', 'sku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_items');
    }
};
