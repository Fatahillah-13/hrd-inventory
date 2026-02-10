<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Order ATK</h2>
            <a href="{{ route('atk.catalog') }}" class="px-4 py-2 border rounded">Katalog</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left p-3">ID</th>
                            <th class="text-left p-3">Divisi</th>
                            <th class="text-left p-3">Status</th>
                            <th class="text-left p-3">Tanggal</th>
                            <th class="text-right p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $o)
                            <tr class="border-t">
                                <td class="p-3">#{{ $o->id }}</td>
                                <td class="p-3">{{ $o->division->name }}</td>
                                <td class="p-3">{{ $o->status }}</td>
                                <td class="p-3">{{ $o->created_at->format('d-m-Y H:i') }}</td>
                                <td class="p-3 text-right">
                                    <a class="text-blue-600" href="{{ route('atk.orders.show', $o) }}">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="p-3" colspan="5">Belum ada order.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
