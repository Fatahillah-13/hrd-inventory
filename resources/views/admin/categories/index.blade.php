<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kategori</h2>
            <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 bg-gray-900 text-white rounded">
                Tambah
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('success'))
                <div class="p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white p-4 rounded shadow">
                <form method="GET" class="flex gap-2">
                    <input name="q" value="{{ $q }}" placeholder="Cari kategori..."
                        class="border rounded px-3 py-2 w-full" />
                    <button class="px-4 py-2 bg-gray-900 text-white rounded">Cari</button>
                </form>
            </div>

            <div class="bg-white rounded shadow overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left p-3">Nama</th>
                            <th class="text-left p-3">Aktif</th>
                            <th class="text-right p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                            <tr class="border-t">
                                <td class="p-3">{{ $cat->name }}</td>
                                <td class="p-3">{{ $cat->is_active ? 'Ya' : 'Tidak' }}</td>
                                <td class="p-3 text-right space-x-2">
                                    <a class="text-blue-600" href="{{ route('admin.categories.edit', $cat) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}"
                                        class="inline" onsubmit="return confirm('Hapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="p-3" colspan="3">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout>
