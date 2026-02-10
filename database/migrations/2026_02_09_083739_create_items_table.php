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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->restrictOnDelete();

            $table->boolean('is_atk')->default(false);
            $table->boolean('is_loanable')->default(false);

            // routing peminjaman: divisi HRD penanggung jawab barang
            $table->foreignId('responsible_division_id')
                ->nullable()
                ->constrained('divisions')
                ->nullOnDelete();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['name', 'category_id']);
            $table->index(['is_atk', 'is_loanable', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
