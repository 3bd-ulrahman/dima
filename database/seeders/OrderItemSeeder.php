<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        Order::all()->each(function (Order $order): void {
            OrderItem::factory(3)
                ->recycle($order)
                ->recycle(Product::all())
                ->create();
        });
    }
}
