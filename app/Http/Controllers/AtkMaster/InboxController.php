<?php

namespace App\Http\Controllers\AtkMaster;

use App\Http\Controllers\Controller;
use App\Models\AtkOrder;
use App\Models\AtkOrderStatusHistory;
use App\Services\AtkOrderStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InboxController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString() ?: 'submitted';
        $orders = AtkOrder::query()
            ->with(['division', 'requester'])
            ->where('status', $status)
            ->orderBy('created_at')
            ->paginate(10);

        return view('atk_master.inbox', compact('orders', 'status'));
    }

    public function bulkDecision(Request $request)
    {
        $data = $request->validate([
            'order_ids' => ['required', 'array', 'min:1'],
            'order_ids.*' => ['integer', 'exists:atk_orders,id'],
            'action' => ['required', 'in:approve,reject'],
            'rejected_reason' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($data['action'] === 'reject' && empty($data['rejected_reason'])) {
            return back()->with('error', 'Alasan wajib diisi untuk reject.');
        }

        $userId = Auth::id();

        DB::transaction(function () use ($data, $userId) {
            $orders = AtkOrder::whereIn('id', $data['order_ids'])
                ->lockForUpdate()
                ->get();

            foreach ($orders as $order) {
                // hanya proses yang masih submitted
                if ($order->status !== 'submitted') {
                    continue;
                }

                $from = $order->status;

                if ($data['action'] === 'approve') {
                    $order->update([
                        'status' => 'approved',
                        'approved_at' => now(),
                        'rejected_reason' => null,
                        'rejected_at' => null,
                    ]);

                    // item ikut approved (step 3)
                    $order->items()->update(['status' => 'approved']);

                    AtkOrderStatusService::recalcAndUpdate($order, $userId, 'Auto set to processing after approve.');

                    AtkOrderStatusHistory::create([
                        'atk_order_id' => $order->id,
                        'from_status' => $from,
                        'to_status' => 'approved',
                        'changed_by' => $userId,
                        'note' => 'Order di-approve oleh atk_master.',
                    ]);
                } else {
                    $order->update([
                        'status' => 'rejected',
                        'rejected_at' => now(),
                        'rejected_reason' => $data['rejected_reason'],
                    ]);

                    $order->items()->update(['status' => 'rejected']);

                    AtkOrderStatusHistory::create([
                        'atk_order_id' => $order->id,
                        'from_status' => $from,
                        'to_status' => 'rejected',
                        'changed_by' => $userId,
                        'note' => 'Order di-reject: '.$data['rejected_reason'],
                    ]);
                }
            }
        });

        return back()->with('success', 'Bulk keputusan berhasil diproses.');
    }
}
