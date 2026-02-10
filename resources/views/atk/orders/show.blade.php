<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Order #{{ $order->id }}</h2>
            <a href="{{ route('atk.orders.index') }}" class="px-4 py-2 border rounded">Kembali</a>
        </div>
    </x-slot>

    <div class="py-6">
        <a href="{{ route('atk.orders.collect.show', $order) }}" class="px-4 py-2 border rounded">
            Ambil Barang
        </a>
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="bg-white p-4 rounded shadow space-y-2">
                <div><b>Status:</b> {{ $order->status }}</div>
                <div><b>Divisi:</b> {{ $order->division->name }}</div>
                @if ($order->status === 'rejected')
                    <div class="p-3 bg-red-100 text-red-800 rounded">
                        <b>Alasan reject:</b> {{ $order->rejected_reason }}
                    </div>
                @endif
            </div>

            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left p-3">Barang</th>
                            <th class="text-left p-3">Qty Requested</th>
                            <th class="text-left p-3">Qty Ready</th>
                            <th class="text-left p-3">Status Item</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $line)
                            <tr class="border-t">
                                <td class="p-3">{{ $line->item->name }}</td>
                                <td class="p-3">{{ $line->qty_requested }}</td>
                                <td class="p-3">{{ $line->qty_ready }}</td>
                                <td class="p-3">{{ $line->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <div class="font-semibold mb-2">Riwayat Status</div>
                <ul class="text-sm space-y-1">
                    @foreach ($order->histories as $h)
                        <li>
                            {{ $h->created_at->format('d-m-Y H:i') }}
                            — {{ $h->from_status ?? '-' }} → <b>{{ $h->to_status }}</b>
                            @if ($h->changer)
                                ({{ $h->changer->name }})
                            @endif
                            @if ($h->note)
                                — <span class="text-gray-600">{{ $h->note }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>
