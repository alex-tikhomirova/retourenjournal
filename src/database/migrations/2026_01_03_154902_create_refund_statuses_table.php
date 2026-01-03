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
        Schema::create('refund_statuses', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('code')->unique();
            $table->string('name');
            $table->boolean('is_counted')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_statuses');
    }
};
