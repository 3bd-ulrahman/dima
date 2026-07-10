<?php

declare(strict_types=1);

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/{category}', [CategoryController::class, 'show'])->name('categories.show');
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/{product:slug}', [ProductController::class, 'show'])->name('products.show');
});

Route::middleware('auth')->group(function () {
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add', [CartController::class, 'add'])->name('cart.add');
        Route::patch('/{itemId}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/{itemId}', [CartController::class, 'remove'])->name('cart.remove');
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
        Route::post('/', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
    });
});

require __DIR__.'/auth.php';

Route::get('test', function () {
    $images = [
        '742278454_1659936365100218_4773972997069753992_n.jpeg',
        '742324489_2117291749191621_8145197379333982129_n.jpeg',
        '742558523_911255742001729_8600678665568402185_n.jpeg',
        '742581232_1687924219105419_450630892925040702_n.jpeg',
        '742943120_1762146584959979_3882500268190171693_n.jpeg',
    ];

    $products = Product::inRandomOrder()->limit(5)->get();

    foreach ($products as $index => $product) {
        $product->addMedia(public_path($images[$index]))
            ->toMediaCollection('images');
    }

    return 'Done! Attached 5 images to random products.';
});
