<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">ATK Master - Rekap Permintaan</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="bg-white p-4 rounded shadow">
                <form method="GET" class="flex flex-wrap gap-2 items-end">
                    <div>
                        <label class="block text-sm">Dari</label>
                        <input type="date" name="from" value="{{ $from }}" class="border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm">Sampai</label>
                        <input type="date" name="to" value="{{ $to }}"
                            class="border rounded px-3 py-2">
                    </div>
                    <button class="px-4 py-2 bg-gray-900 text-white rounded">Filter</button>

                    <a href="{{ route('atk_master.recap.export', ['from' => $from, 'to' => $to]) }}"
                        class="px-4 py-2 border rounded">
                        Export Excel
                    </a>

                    <a href="{{ route('atk_master.inbox') }}" class="px-4 py-2 border rounded">
                        Kembali ke Inbox
                    </a>
                </form>
            </div>

            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left p-3">Nama Barang</th>
                            <th class="text-left p-3">Quantity</th>
                            <th class="text-left p-3">Divisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $r)
                            <tr class="border-t">
                                <td class="p-3">{{ $r->item_name }}</td>
                                <td class="p-3">{{ (int) $r->total_qty }}</td>
                                <td class="p-3">{{ $r->division_name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="p-3" colspan="3">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $rows->links() }}
        </div>
    </div>
</x-app-layout>
