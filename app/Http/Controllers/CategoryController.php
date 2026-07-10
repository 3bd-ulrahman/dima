<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(Request $request): Response
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function show(Request $request, Category $category): Response
    {
        $products = $category->products()
            ->with('media')
            ->where('in_stock', true)
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Categories/Show', [
            'category' => $category->loadCount('products'),
            'products' => $products,
        ]);
    }
}
