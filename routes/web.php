<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Atk\CartController;
use App\Http\Controllers\Atk\CatalogController;
use App\Http\Controllers\Atk\OrderController;
use App\Http\Controllers\AtkMaster\InboxController;
use App\Http\Controllers\AtkMaster\RecapController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'));

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
});

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('units', UnitController::class)->except(['show']);
        Route::resource('items', ItemController::class)->except(['show']);
    });

    // ATK Admin_divisi routes
    Route::middleware(['role:admin_divisi'])->prefix('atk')->name('atk.')->group(function () {
        Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
        Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
        Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    });

    // ATK Master routes
    Route::middleware(['role:atk_master'])->prefix('atk-master')->name('atk_master.')->group(function () {
        Route::get('/inbox', [InboxController::class, 'index'])->name('inbox');
        Route::post('/bulk-decision', [InboxController::class, 'bulkDecision'])->name('bulk_decision');
        Route::get('/recap', [RecapController::class, 'index'])->name('recap');
        Route::get('/recap/export', [RecapController::class, 'export'])->name('recap.export');
    });

});

require __DIR__.'/auth.php';
