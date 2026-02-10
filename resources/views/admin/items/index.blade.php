<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Barang</h2>
            <a href="{{ route('admin.items.create') }}"
               class="px-4 py-2 bg-gray-900 text-white rounded">Tambah</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if(session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white p-4 rounded shadow">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <input name="q" value="{{ $q }}" placeholder="Cari barang..."
                           class="border rounded px-3 py-2" />

                    <select name="category_id" class="border rounded px-3 py-2">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected((int)$categoryId === $cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="only_atk" value="1" @checked($onlyAtk) />
                        <span>ATK saja</span>
                    </label>

                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="only_loanable" value="1" @checked($onlyLoanable) />
                        <span>Bisa dipinjam</span>
                    </label>

                    <button class="px-4 py-2 bg-gray-900 text-white rounded">Filter</button>
                </form>
            </div>

            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left p-3">Nama</th>
                            <th class="text-left p-3">Kategori</th>
                            <th class="text-left p-3">Satuan</th>
                            <th class="text-left p-3">ATK</th>
                            <th class="text-left p-3">Loanable</th>
                            <th class="text-left p-3">Penanggung Jawab</th>
                            <th class="text-left p-3">Aktif</th>
                            <th class="text-right p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr class="border-t">
                                <td class="p-3">{{ $item->name }}</td>
                                <td class="p-3">{{ $item->category->name }}</td>
                                <td class="p-3">{{ $item->unit->name }}</td>
                                <td class="p-3">{{ $item->is_atk ? 'Ya' : 'Tidak' }}</td>
                                <td class="p-3">{{ $item->is_loanable ? 'Ya' : 'Tidak' }}</td>
                                <td class="p-3">
                                    {{ $item->responsibleDivision?->name ?? '-' }}
                                </td>
                                <td class="p-3">{{ $item->is_active ? 'Ya' : 'Tidak' }}</td>
                                <td class="p-3 text-right space-x-2">
                                    <a href="{{ route('admin.items.edit', $item) }}"
                                       class="text-blue-600">Edit</a>

                                    <form method="POST" action="{{ route('admin.items.destroy', $item) }}"
                                          class="inline"
                                          onsubmit="return confirm('Hapus barang ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="p-3" colspan="8">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>
                {{ $items->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
