<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder
{
    public function run(): void
    {
        Cart::all()->each(function (Cart $cart): void {
            CartItem::factory(3)
                ->recycle($cart)
                ->recycle(Product::all())
                ->create();
        });
    }
}
