<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\IndexCategoryRequest;
use App\Models\Category;
use App\Support\Pagination;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(IndexCategoryRequest $request): Response
    {
        $perPage = $request->integer('per_page', Pagination::DEFAULT_PER_PAGE);

        $categories = Category::withCount('products')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Dashboard/Categories/Index', [
            'categories' => $categories,
            'filters' => [
                'per_page' => $perPage,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name',
            ],
        ]);

        Category::create([
            'name' => $validated['name'],
        ]);

        return back()->with('success', "{$validated['name']} was created.");
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name,' . $category->id,
            ],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('success', "{$validated['name']} was updated.");
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->loadCount('products');

        if ($category->products_count > 0) {
            $products = $category->products_count;

            return back()->with('error', "{$category->name} still has {$products} product(s). Reassign them before deleting.");
        }

        $name = $category->name;

        $category->delete();

        return back()->with('success', "{$name} was deleted.");
    }
}
