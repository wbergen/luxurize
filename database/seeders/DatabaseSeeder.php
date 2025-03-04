<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserGroup;
use App\Models\UserRole;
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
            'role_id' => 3
        ]);
        User::factory(10)->create();

        OrderStatus::factory()
            ->create(['label' => 'Wating On Provider'])     // 1
            ->create(['label' => 'Unfulfilled'])            // 2
            ->create(['label' => 'Fullfilled']);            // 3


//        ProductCategory::factory(10)->for($products)->create();

//        $productCategories = ProductCategory::factory()->create();
//        Product::factory(10)->for($productCategories)->create();

        $products = Product::factory(6)
            ->has(Category::factory(2))
            ->has(Tag::factory(4));

        Order::factory(4)
            ->has($products)
            ->create();




        UserGroup::factory()->create(['id' => 1, 'label' => 'Basic'])
            ->create(['id' => 2, 'label' => 'Power'])
            ->create(['id' => 3, 'label' => 'Full']);
        UserRole::factory()
            ->create(['label' => 'User', 'user_group_id' => 1])
            ->create(['label' => 'Power User', 'user_group_id' => 2])
            ->create(['label' => 'Administrator', 'user_group_id' => 3]);
    }
}
