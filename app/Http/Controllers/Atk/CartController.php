<?php

namespace App\Http\Controllers\Atk;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private string $sessionKey = 'atk_cart';

    public function show()
    {
        $cart = session()->get($this->sessionKey, []);
        $items = Item::with(['category', 'unit'])
            ->whereIn('id', array_keys($cart))
            ->orderBy('name')
            ->get();

        return view('atk.cart', compact('cart', 'items'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'qty' => ['required', 'integer', 'min:1', 'max:9999'],
        ]);

        $item = Item::where('id', $data['item_id'])
            ->where('is_active', true)
            ->where('is_atk', true)
            ->firstOrFail();

        $cart = session()->get($this->sessionKey, []);
        $cart[$item->id] = ($cart[$item->id] ?? 0) + (int)$data['qty'];

        session()->put($this->sessionKey, $cart);

        return back()->with('success', 'Barang ditambahkan ke keranjang.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'qty' => ['required', 'array'],
            'qty.*' => ['required', 'integer', 'min:1', 'max:9999'],
        ]);

        $cart = session()->get($this->sessionKey, []);
        foreach ($data['qty'] as $itemId => $qty) {
            if (isset($cart[$itemId])) {
                $cart[$itemId] = (int)$qty;
            }
        }

        session()->put($this->sessionKey, $cart);

        return back()->with('success', 'Keranjang diupdate.');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'item_id' => ['required', 'integer'],
        ]);

        $cart = session()->get($this->sessionKey, []);
        unset($cart[$data['item_id']]);

        session()->put($this->sessionKey, $cart);

        return back()->with('success', 'Barang dihapus dari keranjang.');
    }
}
