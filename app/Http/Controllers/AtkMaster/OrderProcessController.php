<?php

namespace App\Http\Controllers\AtkMaster;

use App\Http\Controllers\Controller;
use App\Models\AtkOrder;
use App\Services\AtkOrderStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderProcessController extends Controller
{
    public function show(AtkOrder $order)
    {
        $order->load(['division', 'requester', 'items.item.unit']);

        return view('atk_master.orders.show', compact('order'));
    }

    public function setReady(Request $request, AtkOrder $order)
    {
        $data = $request->validate([
            'lines' => ['required', 'array'],
            'lines.*.id' => ['required', 'integer', 'exists:atk_order_items,id'],
            'lines.*.qty_ready' => ['required', 'integer', 'min:0', 'max:999999'],
        ]);

        // hanya order approved/processing/ready_to_collect boleh diproses
        if (! in_array($order->status, ['approved', 'processing', 'ready_to_collect'], true)) {
            return back()->with('error', 'Order tidak bisa diproses pada status ini.');
        }

        $userId = $request->user()->id;

        DB::transaction(function () use ($order, $data, $userId) {
            $order->load('items');

            foreach ($data['lines'] as $line) {
                $itemLine = $order->items->firstWhere('id', (int) $line['id']);
                if (! $itemLine) {
                    continue;
                }

                // qty_ready tidak boleh > qty_requested
                $newReady = min((int) $line['qty_ready'], (int) $itemLine->qty_requested);

                // qty_ready tidak boleh < qty_collected
                $newReady = max($newReady, (int) $itemLine->qty_collected);

                $itemLine->qty_ready = $newReady;

                // status per item
                if ($itemLine->qty_ready >= $itemLine->qty_requested) {
                    $itemLine->status = 'ready';
                    $itemLine->ready_at = $itemLine->ready_at ?? now();
                } else {
                    $itemLine->status = 'approved'; // masih menunggu sebagian
                    $itemLine->ready_at = null;
                }

                $itemLine->save();
            }

            // recalculates order status
            AtkOrderStatusService::recalcAndUpdate($order, $userId, 'ATK Master update qty_ready.');
        });

        return back()->with('success', 'Update siap diambil berhasil.');
    }
}
