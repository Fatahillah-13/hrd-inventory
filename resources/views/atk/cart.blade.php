<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Keranjang ATK</h2>
            <a href="{{ route('atk.catalog') }}" class="px-4 py-2 border rounded">Kembali</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white p-4 rounded shadow">
                @if ($totalLines === 0)
                    <div>Keranjang kosong.</div>
                @else
                    <form method="POST" action="{{ route('atk.cart.update') }}" class="space-y-3">
                        @csrf
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left p-3">Barang</th>
                                        <th class="text-left p-3">Qty</th>
                                        <th class="text-left p-3">Satuan</th>
                                        <th class="text-right p-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart as $line)
                                        <tr class="border-t">
                                            <td class="p-3">{{ $line['name'] }}</td>
                                            <td class="p-3">
                                                <input type="hidden" name="items[][item_id]"
                                                    value="{{ $line['item_id'] }}">
                                                <input type="number" name="items[][qty]" min="1" max="1000"
                                                    value="{{ $line['qty'] }}" class="border rounded px-3 py-2 w-28">
                                            </td>
                                            <td class="p-3">{{ $line['unit'] }}</td>
                                            <td class="p-3 text-right">
                                                <form id="remove-{{ $line['item_id'] }}" method="POST"
                                                    action="{{ route('atk.cart.remove') }}">
                                                    @csrf
                                                    <input type="hidden" name="item_id"
                                                        value="{{ $line['item_id'] }}">
                                                </form>

                                                <button form="remove-{{ $line['item_id'] }}" class="text-red-600"
                                                    onclick="return confirm('Hapus item ini?')">
                                                    Hapus
                                                </button>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button class="px-4 py-2 border rounded">Update Keranjang</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('atk.checkout') }}" class="flex justify-end mt-4">
                        @csrf
                        <button class="px-4 py-2 bg-gray-900 text-white rounded"
                            onclick="return confirm('Checkout dan buat order?')">
                            Checkout
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
