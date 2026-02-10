<?php

namespace App\Services;

use App\Models\AtkOrder;
use App\Models\AtkOrderStatusHistory;

class AtkOrderStatusService
{
    public static function recalcAndUpdate(AtkOrder $order, ?int $changedBy = null, ?string $note = null): void
    {
        $order->loadMissing('items');

        // jangan sentuh kalau rejected/submitted
        if (in_array($order->status, ['submitted', 'rejected'], true)) {
            return;
        }

        $totalRequested = 0;
        $totalReady = 0;
        $totalCollected = 0;

        foreach ($order->items as $it) {
            $totalRequested += (int) $it->qty_requested;
            $totalReady += (int) $it->qty_ready;
            $totalCollected += (int) $it->qty_collected;
        }

        $from = $order->status;
        $to = $from;

        // order-level status logic
        if ($totalCollected >= $totalRequested && $totalRequested > 0) {
            $to = 'finished';
        } elseif ($totalReady >= $totalRequested && $totalRequested > 0) {
            $to = 'ready_to_collect';
        } else {
            // sudah approved tapi belum semua ready
            $to = 'processing';
        }

        if ($to !== $from) {
            $order->update(['status' => $to]);

            AtkOrderStatusHistory::create([
                'atk_order_id' => $order->id,
                'from_status' => $from,
                'to_status' => $to,
                'changed_by' => $changedBy,
                'note' => $note,
            ]);
        }
    }
}
