<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Permintaan ATK</h2>
            <a href="{{ route('atk.cart.show') }}" class="px-4 py-2 bg-gray-900 text-white rounded">
                Keranjang
            </a>
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

            <div class="bg-white p-4 rounded shadow">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <input name="q" value="{{ $q }}" placeholder="Cari ATK..."
                        class="border rounded px-3 py-2" />
                    <select name="category_id" class="border rounded px-3 py-2">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected((int) $categoryId === $cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    <button class="px-4 py-2 bg-gray-900 text-white rounded">Filter</button>
                    <a href="{{ route('atk.orders.index') }}" class="px-4 py-2 border rounded text-center">Riwayat
                        Order</a>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($items as $item)
                    <div class="bg-white p-4 rounded shadow space-y-2">
                        <div class="font-semibold">{{ $item->name }}</div>
                        <div class="text-sm text-gray-600">
                            {{ $item->category->name }} • {{ $item->unit->name }}
                        </div>

                        <form method="POST" action="{{ route('atk.cart.add') }}" class="flex gap-2 items-center">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                            <input type="number" name="qty" min="1" value="1"
                                class="border rounded px-3 py-2 w-24">
                            <button class="px-3 py-2 bg-gray-900 text-white rounded">Tambah</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div>
                {{ $items->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
