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
        Schema::create('atk_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atk_order_id')->constrained('atk_orders')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->restrictOnDelete();

            $table->unsignedInteger('qty_requested');

            // fulfillment status per item (step 3 fokus sampai approved/rejected dulu)
            $table->string('status')->default('requested'); // requested, approved, rejected (nanti: ordered/arrived/ready/collected)

            $table->timestamps();

             $table->unique(['atk_order_id', 'item_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atk_order_items');
    }
};
