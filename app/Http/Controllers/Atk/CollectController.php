<?php

namespace App\Http\Controllers\Atk;

use App\Http\Controllers\Controller;
use App\Models\AtkOrder;
use App\Services\AtkOrderStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectController extends Controller
{
    public function show(Request $request, AtkOrder $order)
    {
        $user = $request->user();
        abort_unless($order->requested_by === $user->id, 403);

        $order->load(['division', 'items.item.unit']);

        return view('atk.orders.collect', compact('order'));
    }

    public function collect(Request $request, AtkOrder $order)
    {
        $user = $request->user();
        abort_unless($order->requested_by === $user->id, 403);

        $data = $request->validate([
            'lines' => ['required', 'array'],
            'lines.*.id' => ['required', 'integer', 'exists:atk_order_items,id'],
            'lines.*.qty_collect' => ['required', 'integer', 'min:0', 'max:999999'],
        ]);

        // hanya boleh collect saat order minimal processing/ready_to_collect
        if (! in_array($order->status, ['processing', 'ready_to_collect'], true)) {
            return back()->with('error', 'Order belum bisa diambil pada status ini.');
        }

        $userId = $user->id;

        DB::transaction(function () use ($order, $data, $userId) {
            $order->load('items');

            foreach ($data['lines'] as $line) {
                $itemLine = $order->items->firstWhere('id', (int) $line['id']);
                if (! $itemLine) {
                    continue;
                }

                // maksimal boleh ambil sampai qty_ready
                $maxCollectable = (int) $itemLine->qty_ready;

                $newCollected = min((int) $line['qty_collect'], $maxCollectable);

                // qty_collected tidak boleh turun (biar audit aman)
                $newCollected = max($newCollected, (int) $itemLine->qty_collected);

                $itemLine->qty_collected = $newCollected;

                if ($itemLine->qty_collected >= $itemLine->qty_requested) {
                    $itemLine->status = 'collected';
                    $itemLine->collected_at = $itemLine->collected_at ?? now();
                } else {
                    // belum selesai diambil (walaupun ready mungkin sebagian)
                    $itemLine->status = ($itemLine->qty_ready > 0) ? 'ready' : $itemLine->status;
                    $itemLine->collected_at = null;
                }

                $itemLine->save();
            }

            AtkOrderStatusService::recalcAndUpdate($order, $userId, 'Admin divisi update qty_collected.');
        });

        return back()->with('success', 'Pengambilan berhasil diupdate.');
    }
}
