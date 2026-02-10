@csrf

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium">Nama</label>
        <input name="name" value="{{ old('name', $unit->name ?? '') }}" class="border rounded px-3 py-2 w-full"
            required>
        @error('name')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Simbol (opsional)</label>
        <input name="symbol" value="{{ old('symbol', $unit->symbol ?? '') }}" class="border rounded px-3 py-2 w-full"
            placeholder="pcs / box / rim ...">
        @error('symbol')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror
    </div>

    <label class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $unit->is_active ?? true))>
        <span>Aktif</span>
    </label>

    @if ($errors->any())
        <div class="p-3 bg-red-100 text-red-800 rounded">Mohon periksa input yang error.</div>
    @endif

    <div class="flex justify-end gap-2">
        <a href="{{ route('admin.units.index') }}" class="px-4 py-2 border rounded">Batal</a>
        <button class="px-4 py-2 bg-gray-900 text-white rounded">Simpan</button>
    </div>
</div>
