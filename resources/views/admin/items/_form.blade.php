@csrf

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Nama</label>
        <input name="name" value="{{ old('name', $item->name ?? '') }}" class="border rounded px-3 py-2 w-full"
            required>
        @error('name')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Kategori</label>
            <select name="category_id" class="border rounded px-3 py-2 w-full" required>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id', $item->category_id ?? null) == $cat->id)>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Satuan</label>
            <select name="unit_id" class="border rounded px-3 py-2 w-full" required>
                @foreach ($units as $u)
                    <option value="{{ $u->id }}" @selected(old('unit_id', $item->unit_id ?? null) == $u->id)>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>
            @error('unit_id')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_atk" value="1" @checked(old('is_atk', $item->is_atk ?? false))>
            <span>Masuk katalog ATK</span>
        </label>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_loanable" value="1" @checked(old('is_loanable', $item->is_loanable ?? false))>
            <span>Bisa dipinjam</span>
        </label>

        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true))>
            <span>Aktif</span>
        </label>
    </div>

    <div>
        <label class="block text-sm font-medium">Penanggung Jawab (Divisi HRD) - hanya untuk loanable</label>
        <select name="responsible_division_id" class="border rounded px-3 py-2 w-full">
            <option value="">-</option>
            @foreach ($divisions as $d)
                <option value="{{ $d->id }}" @selected(old('responsible_division_id', $item->responsible_division_id ?? null) == $d->id)>
                    {{ $d->name }}
                </option>
            @endforeach
        </select>
        @error('responsible_division_id')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror
        <div class="text-xs text-gray-500 mt-1">Jika barang tidak loanable, field ini akan diabaikan.</div>
    </div>

    @if ($errors->any())
        <div class="p-3 bg-red-100 text-red-800 rounded">
            Mohon periksa input yang error.
        </div>
    @endif

    <div class="flex justify-end gap-2">
        <a href="{{ route('admin.items.index') }}" class="px-4 py-2 border rounded">Batal</a>
        <button class="px-4 py-2 bg-gray-900 text-white rounded">Simpan</button>
    </div>
</div>
