<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proses Order #{{ $order->id }}</h2>
            <a href="{{ route('atk_master.inbox') }}" class="px-4 py-2 border rounded">Kembali</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white p-4 rounded shadow space-y-1">
                <div><b>Status:</b> {{ $order->status }}</div>
                <div><b>Divisi:</b> {{ $order->division->name }}</div>
                <div><b>Requester:</b> {{ $order->requester->name }}</div>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <form method="POST" action="{{ route('atk_master.orders.set_ready', $order) }}" class="space-y-3">
                    @csrf

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="text-left p-3">Barang</th>
                                    <th class="text-left p-3">Requested</th>
                                    <th class="text-left p-3">Ready (siap diambil)</th>
                                    <th class="text-left p-3">Collected (diambil)</th>
                                    <th class="text-left p-3">Satuan</th>
                                    <th class="text-left p-3">Status Item</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $idx => $line)
                                    <tr class="border-t">
                                        <td class="p-3">{{ $line->item->name }}</td>
                                        <td class="p-3">{{ $line->qty_requested }}</td>
                                        <td class="p-3">
                                            <input type="hidden" name="lines[{{ $idx }}][id]"
                                                value="{{ $line->id }}">
                                            <input type="number" min="{{ $line->qty_collected }}"
                                                max="{{ $line->qty_requested }}"
                                                name="lines[{{ $idx }}][qty_ready]"
                                                value="{{ $line->qty_ready }}" class="border rounded px-3 py-2 w-28">
                                        </td>
                                        <td class="p-3">{{ $line->qty_collected }}</td>
                                        <td class="p-3">{{ $line->item->unit->name }}</td>
                                        <td class="p-3">{{ $line->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end">
                        <button class="px-4 py-2 bg-gray-900 text-white rounded"
                            onclick="return confirm('Simpan perubahan qty_ready?')">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
