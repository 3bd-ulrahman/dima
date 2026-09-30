<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\Pagination;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(Request $request): Response
    {
        $validated = $request->validate([
            'search' => ['sometimes', 'string', 'max:50'],
            'sort' => ['sometimes', 'string', Rule::in(['name_asc', 'name_desc', 'newest', 'oldest'])],
            'per_page' => Pagination::PER_PAGE_RULES,
        ]);

        $search = (string) ($validated['search'] ?? '');
        $sort = (string) ($validated['sort'] ?? '');
        $perPage = (int) ($validated['per_page'] ?? Pagination::DEFAULT_PER_PAGE);

        [$sortColumn, $sortDirection] = $this->resolveSort($sort);

        $categories = Category::withCount('products')
            ->when($search !== '', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy($sortColumn, $sortDirection)
            ->orderBy('id')
            ->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Dashboard/Categories/Index', [
            'categories' => $categories,
            'filters' => [
                'search' => $search,
                'sort' => $sort,
                'per_page' => $perPage,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('success', "{$validated['name']} was created.");
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate($this->rules($category));

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

    /**
     * The slug is never accepted from the request, so it is derived from the
     * name and checked here instead. Validating the derived value is what
     * keeps "Fresh Produce" and "Fresh-Produce" from both resolving to
     * "fresh-produce" and tripping the database index.
     *
     * @return array<string, array<int, mixed>>
     */
    private function rules(?Category $category = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, Closure $fail) use ($category): void {
                    $this->ensureSlugIsAvailable($value, $category, $fail);
                },
            ],
        ];
    }

    private function ensureSlugIsAvailable(mixed $name, ?Category $category, Closure $fail): void
    {
        $slug = Str::slug(is_string($name) ? $name : '');

        if ($slug === '') {
            $fail('The name must contain at least one letter or number.');

            return;
        }

        $query = Category::query()->where('slug', $slug);

        if ($category instanceof Category) {
            $query->whereKeyNot($category->getKey());
        }

        if ($query->exists()) {
            $fail("Another category already uses the slug \"{$slug}\". Choose a different name.");
        }
    }

    /**
     * @return array{0: string, 1: string}
     */
    /**
     * The default arm only covers an absent sort now; anything else is
     * rejected by Rule::in before it reaches the query.
     *
     * @return array{0: string, 1: string}
     */
    private function resolveSort(string $sort): array
    {
        return match ($sort) {
            'name_desc' => ['name', 'desc'],
            'newest' => ['created_at', 'desc'],
            'oldest' => ['created_at', 'asc'],
            default => ['name', 'asc'],
        };
    }
}
