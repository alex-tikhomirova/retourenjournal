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
        Schema::create('shipment_statuses', function (Blueprint $table) {
            $table->id(); // bigint

            // Keep it stable for seeds / internal logic
            $table->string('code')->unique(); // created, shipped, in_transit, delivered, returned, cancelled
            $table->string('name');           // label for UI

            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_terminal')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_statuses');
    }
};
