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
        Schema::create('return_statuses', function (Blueprint $table) {
            $table->id(); // bigint PK

            // NULL = global default status
            // NOT NULL = organization-specific custom status
            $table->foreignId('organization_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            // Stable key for seeds / API / internal logic
            $table->string('code');

            // Label shown in UI
            $table->string('name');

            $table->unsignedSmallInteger('sort_order')->default(0);

            // 1=initial, 2=normal, 9=terminal
            $table->unsignedSmallInteger('kind')->default(2);


            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['organization_id', 'code']);
            $table->index(['organization_id', 'sort_order']);
            $table->index(['organization_id', 'is_active']);
            $table->index(['organization_id', 'kind']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_statuses');
    }
};
