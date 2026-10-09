<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Product;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index renders the categories listing', function (): void {
    Category::factory()->create(['name' => 'Fresh Produce']);

    get(route('dashboard.categories.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('Dashboard/Categories/Index')
            ->has('categories.data', 1)
            ->where('categories.data.0.name', 'Fresh Produce')
            ->has('filters')
        );
});

test('index paginates the categories', function (): void {
    Category::factory(20)->create();

    get(route('dashboard.categories.index', ['per_page' => 10]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('categories.data', 10)
            ->where('categories.total', 20)
            ->where('categories.per_page', 10)
        );
});

test('index uses the shared default per page when none is given', function (): void {
    get(route('dashboard.categories.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('categories.per_page', 10)
        );
});

test('index rejects a per page above the shared maximum', function (): void {
    get(route('dashboard.categories.index', ['per_page' => 9999]))
        ->assertSessionHasErrors('per_page');
});

test('index rejects a per page below the shared minimum', function (): void {
    get(route('dashboard.categories.index', ['per_page' => 5]))
        ->assertSessionHasErrors('per_page');
});

test('index rejects a non-numeric per page', function (): void {
    get(route('dashboard.categories.index', ['per_page' => 'abc']))
        ->assertSessionHasErrors('per_page');
});

test('index accepts the shared maximum', function (): void {
    get(route('dashboard.categories.index', ['per_page' => 50]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('categories.per_page', 50)
        );
});

test('index orders categories by name ascending', function (): void {
    Category::factory()->create(['name' => 'Bananas']);
    Category::factory()->create(['name' => 'Apples']);

    get(route('dashboard.categories.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('categories.data.0.name', 'Apples')
            ->where('categories.data.1.name', 'Bananas')
        );
});

test('index accepts a page query parameter', function (): void {
    Category::factory(20)->create();

    get(route('dashboard.categories.index', ['per_page' => 10, 'page' => 2]))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('categories.data', 10)
            ->where('categories.current_page', 2)
            ->where('categories.total', 20)
        );
});

test('index rejects a non-numeric page', function (): void {
    get(route('dashboard.categories.index', ['page' => 'abc']))
        ->assertSessionHasErrors('page');
});

test('index ignores an unknown query filter', function (): void {
    Category::factory()->create(['name' => 'Fresh Produce']);

    get(route('dashboard.categories.index', ['bogus' => '1']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('categories.data', 1)
        );
});

test('index includes the product count for each category', function (): void {
    $category = Category::factory()->create();
    Product::factory(3)->recycle($category)->create();

    get(route('dashboard.categories.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->where('categories.data.0.products_count', 3)
        );
});

test('store creates a category and generates the slug from the name', function (): void {
    post(route('dashboard.categories.store'), [
        'name' => 'Fresh Produce',
    ])->assertRedirect();

    $this->assertDatabaseHas('categories', [
        'name' => 'Fresh Produce',
        'slug' => 'fresh-produce',
    ]);
});

test('store ignores a slug supplied by the request', function (): void {
    post(route('dashboard.categories.store'), [
        'name' => 'Fresh Produce',
        'slug' => 'organic-produce',
    ])->assertRedirect();

    $this->assertDatabaseHas('categories', [
        'name' => 'Fresh Produce',
        'slug' => 'fresh-produce',
    ]);
});

test('store requires a name', function (): void {
    post(route('dashboard.categories.store'), ['name' => ''])
        ->assertSessionHasErrors('name');

    $this->assertDatabaseEmpty('categories');
});

test('store rejects a name that resolves to an existing slug', function (): void {
    Category::factory()->create(['name' => 'Fresh Produce', 'slug' => 'fresh-produce']);

    post(route('dashboard.categories.store'), [
        'name' => 'Fresh Produce',
    ])->assertSessionHasErrors('name');

    $this->assertDatabaseCount('categories', 1);
});

test('store derives a url safe slug from a messy name', function (): void {
    post(route('dashboard.categories.store'), [
        'name' => '  Fresh   Produce!  ',
    ])->assertRedirect();

    $this->assertDatabaseHas('categories', [
        'name' => 'Fresh   Produce!',
        'slug' => 'fresh-produce',
    ]);
});

test('store rejects a name that collides once the slug is derived', function (): void {
    Category::factory()->create(['slug' => 'fresh-produce']);

    post(route('dashboard.categories.store'), [
        'name' => 'Fresh  Produce',
    ])->assertSessionHasErrors('name');

    $this->assertDatabaseCount('categories', 1);
});

test('store rejects a name that derives no usable slug', function (): void {
    post(route('dashboard.categories.store'), [
        'name' => '日本語',
    ])->assertSessionHasErrors('name');

    $this->assertDatabaseEmpty('categories');
});

test('update changes the name and derives the slug', function (): void {
    $category = Category::factory()->create([
        'name' => 'Old Name',
        'slug' => 'old-name',
    ]);

    put(route('dashboard.categories.update', $category), [
        'name' => 'New Name',
    ])->assertRedirect();

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'New Name',
        'slug' => 'new-name',
    ]);
});

test('update ignores a slug supplied by the request', function (): void {
    $category = Category::factory()->create([
        'name' => 'Old Name',
        'slug' => 'old-name',
    ]);

    put(route('dashboard.categories.update', $category), [
        'name' => 'New Name',
        'slug' => 'injected-slug',
    ])->assertRedirect();

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'slug' => 'new-name',
    ]);
});

test('update allows a category to keep its own slug', function (): void {
    $category = Category::factory()->create([
        'name' => 'Fresh Produce',
        'slug' => 'fresh-produce',
    ]);

    put(route('dashboard.categories.update', $category), [
        'name' => 'Fresh  Produce',
    ])->assertRedirect()->assertSessionHasNoErrors();

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Fresh  Produce',
        'slug' => 'fresh-produce',
    ]);
});

test('update rejects renaming onto another category slug', function (): void {
    $category = Category::factory()->create(['name' => 'Pears', 'slug' => 'pears']);
    Category::factory()->create(['name' => 'Bananas', 'slug' => 'bananas']);

    put(route('dashboard.categories.update', $category), [
        'name' => 'Bananas',
    ])->assertSessionHasErrors('name');

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        'name' => 'Pears',
        'slug' => 'pears',
    ]);
});

test('destroy deletes the category', function (): void {
    $category = Category::factory()->create();

    $this->delete(route('dashboard.categories.destroy', $category))
        ->assertRedirect();

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

test('destroy refuses to delete a category that still has products', function (): void {
    $category = Category::factory()->create();
    Product::factory(2)->recycle($category)->create();

    $this->delete(route('dashboard.categories.destroy', $category))
        ->assertRedirect()
        ->assertSessionHas('error');

    $this->assertDatabaseHas('categories', ['id' => $category->id]);
    $this->assertDatabaseCount('products', 2);
});

test('index is reachable from the named dashboard route', function (): void {
    get(route('dashboard.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('Dashboard/Home'));
});
