<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->unique()->words(3, true),
            'slug' => fn (array $attributes): string => Str::slug($attributes['name']),
            'barcode' => fake()->unique()->ean13(),
            'price' => fake()->randomFloat(2, 1, 100),
            'in_stock' => fake()->boolean(80),
        ];
    }
}
