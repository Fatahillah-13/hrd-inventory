<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();

        $units = Unit::query()
            ->when($q, fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.units.index', compact('units', 'q'));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:units,name'],
            'symbol' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        Unit::create($data);

        return redirect()->route('admin.units.index')->with('success', 'Satuan berhasil dibuat.');
    }

    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:units,name,'.$unit->id],
            'symbol' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $unit->update($data);

        return redirect()->route('admin.units.index')->with('success', 'Satuan berhasil diupdate.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('admin.units.index')->with('success', 'Satuan berhasil dihapus.');
    }
}
