<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        User::all()->each(function (User $user): void {
            Order::factory(3)
                ->recycle($user)
                ->create();
        });
    }
}
