<?php

namespace App\Http\Controllers\Atk;

use App\Http\Controllers\Controller;
use App\Models\AtkOrder;
use App\Models\AtkOrderItem;
use App\Models\AtkOrderStatusHistory;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private string $sessionKey = 'atk_cart';

    public function index()
    {
        $user = Auth::user();

        $orders = AtkOrder::query()
            ->with(['division'])
            ->where('requested_by', $user->id)
            ->orderByDesc('id')
            ->paginate(10);

        return view('atk.orders.index', compact('orders'));
    }

    public function show(AtkOrder $order)
    {
        $user = Auth::user();

        abort_unless($order->requested_by === $user->id, 403);

        $order->load(['division', 'items.item.unit', 'items.item.category', 'histories']);

        return view('atk.orders.show', compact('order'));
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();

        // admin_divisi wajib punya division
        abort_unless($user->division_id, 403);

        $cart = session()->get($this->sessionKey, []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong.');
        }

        $itemIds = array_keys($cart);

        $items = Item::query()
            ->whereIn('id', $itemIds)
            ->where('is_active', true)
            ->where('is_atk', true)
            ->get()
            ->keyBy('id');

        // validasi item di cart masih valid
        foreach ($itemIds as $id) {
            if (!$items->has($id)) {
                return back()->with('error', 'Ada barang di keranjang yang sudah tidak tersedia.');
            }
        }

        DB::transaction(function () use ($user, $cart, $items) {
            $order = AtkOrder::create([
                'requested_by' => $user->id,
                'division_id' => $user->division_id,
                'status' => 'submitted',
            ]);

            foreach ($cart as $itemId => $qty) {
                AtkOrderItem::create([
                    'atk_order_id' => $order->id,
                    'item_id' => (int)$itemId,
                    'qty_requested' => (int)$qty,
                    'status' => 'requested',
                ]);
            }

            AtkOrderStatusHistory::create([
                'atk_order_id' => $order->id,
                'from_status' => null,
                'to_status' => 'submitted',
                'changed_by' => $user->id,
                'note' => 'Order dibuat oleh admin_divisi.',
            ]);
        });

        session()->forget($this->sessionKey);

        return redirect()->route('atk.orders.index')->with('success', 'Order berhasil dikirim.');
    }
}
