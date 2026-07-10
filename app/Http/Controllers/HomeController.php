<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        $products = Product::with('category')
            ->where('in_stock', true)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return Inertia::render('Welcome', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
