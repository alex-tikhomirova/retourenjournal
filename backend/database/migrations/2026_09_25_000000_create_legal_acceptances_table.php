<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_acceptances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('return_id')->nullable()->constrained('returns')->cascadeOnDelete();

            $table->string('actor_type');
            $table->string('document_key');
            $table->string('document_version');
            $table->string('document_hash')->nullable();
            $table->string('action');
            $table->string('context');
            $table->timestamp('accepted_at');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id');
            $table->index('organization_id');
            $table->index('return_id');
            $table->index('document_key');
            $table->index('context');
            $table->index('accepted_at');
            $table->index(['organization_id', 'document_key']);
            $table->index(['user_id', 'document_key']);
            $table->index(['return_id', 'document_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_acceptances');
    }
};
