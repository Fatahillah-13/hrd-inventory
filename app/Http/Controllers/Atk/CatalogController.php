<?php

namespace App\Http\Controllers\Atk;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $categoryId = $request->integer('category_id');

        $items = Item::query()
            ->with(['category', 'unit'])
            ->where('is_active', true)
            ->where('is_atk', true)
            ->when($q, fn($qr) => $qr->where('name', 'like', "%{$q}%"))
            ->when($categoryId, fn($qr) => $qr->where('category_id', $categoryId))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('atk.catalog', compact('items', 'categories', 'q', 'categoryId'));
    }
}
