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
        Schema::table('atk_order_items', function (Blueprint $table) {
            $table->unsignedInteger('qty_ready')->default(0)->after('qty_requested');
            $table->unsignedInteger('qty_collected')->default(0)->after('qty_ready');

            $table->timestamp('ready_at')->nullable()->after('qty_collected');
            $table->timestamp('collected_at')->nullable()->after('ready_at');

            $table->index(['atk_order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('atk_order_items', function (Blueprint $table) {
            $table->dropColumn(['qty_ready', 'qty_collected', 'ready_at', 'collected_at']);
        });
    }
};
