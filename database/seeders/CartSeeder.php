<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        User::all()->each(function (User $user): void {
            Cart::factory()
                ->recycle($user)
                ->create();
        });
    }
}
