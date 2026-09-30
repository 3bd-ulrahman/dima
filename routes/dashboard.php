<?php

declare(strict_types=1);

use App\Http\Controllers\Dashboard\CategoryController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

Route::prefix('dashboard')->name('dashboard.')->group(function (): void {
    Route::get('/', fn (): Response => Inertia::render('Dashboard/Home'))->name('index');

    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
});
