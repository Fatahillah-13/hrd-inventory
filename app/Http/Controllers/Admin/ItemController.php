<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Division;
use App\Models\Item;
use App\Models\Unit;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $categoryId = $request->integer('category_id');
        $onlyAtk = $request->boolean('only_atk');
        $onlyLoanable = $request->boolean('only_loanable');

        $items = Item::query()
            ->with(['category', 'unit', 'responsibleDivision'])
            ->when($q, fn ($query) => $query->where('name', 'like', "%{$q}%"))
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($onlyAtk, fn ($query) => $query->where('is_atk', true))
            ->when($onlyLoanable, fn ($query) => $query->where('is_loanable', true))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.items.index', compact('items', 'categories', 'q', 'categoryId', 'onlyAtk', 'onlyLoanable'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();

        return view('admin.items.create', compact('categories', 'units', 'divisions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'is_atk' => ['nullable', 'boolean'],
            'is_loanable' => ['nullable', 'boolean'],
            'responsible_division_id' => ['nullable', 'exists:divisions,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_atk'] = (bool) ($data['is_atk'] ?? false);
        $data['is_loanable'] = (bool) ($data['is_loanable'] ?? false);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        // Jika tidak loanable, penanggung jawab tidak perlu disimpan
        if (! $data['is_loanable']) {
            $data['responsible_division_id'] = null;
        }

        Item::create($data);

        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil dibuat.');
    }

    public function edit(Item $item)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();

        return view('admin.items.edit', compact('item', 'categories', 'units', 'divisions'));
    }

    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'is_atk' => ['nullable', 'boolean'],
            'is_loanable' => ['nullable', 'boolean'],
            'responsible_division_id' => ['nullable', 'exists:divisions,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_atk'] = (bool) ($data['is_atk'] ?? false);
        $data['is_loanable'] = (bool) ($data['is_loanable'] ?? false);
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        if (! $data['is_loanable']) {
            $data['responsible_division_id'] = null;
        }

        $item->update($data);

        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil diupdate.');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil dihapus.');
    }
}
