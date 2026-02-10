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
        Schema::create('atk_order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atk_order_id')->constrained('atk_orders')->cascadeOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');

            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index(['atk_order_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atk_order_status_histories');
    }
};
