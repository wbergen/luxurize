<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\ProductFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Will',
            'email' => 'will@m7c1.com',
        ]);

        Product::factory(10)->create();

        OrderStatus::factory()
            ->create(['label' => 'Unfulfilled'])
            ->create(['label' => 'Fullfilled']);

//        Order::factory(10)->create();

        Tag::factory(10)->create();
    }
}
