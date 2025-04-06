<?php

namespace Database\Seeders;

use App\Models\ObligationStatus;
use App\Models\Payments\PaymentProviders\Paypal\PaypalPaymentRecord;
use App\Models\Payments\PaymentRecordType;
use App\Models\Products\Category;
use App\Models\Products\Obligables\Meeting;
use App\Models\Products\Obligables\Shippable;
use App\Models\Products\Obligables\Subscription;
use App\Models\Products\Product;
use App\Models\Products\ProductType;
use App\Models\Products\Tag;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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

        ObligationStatus::factory()
            ->create(['id' => 1, 'label' => 'Waiting On Provider'])
            ->create(['id' => 2, 'label' => 'Unfulfilled'])
            ->create(['id' => 3, 'label' => 'Fulfilled']);

        $productTypes = ProductType::factory()
            ->create(['id' => 1, 'label' => 'Shippable', 'class' => Shippable::class])
            ->create(['id' => 2, 'label' => 'Subscription', 'class' => Subscription::class]);
//            ->create(['id' => 3, 'label' => 'Meeting', 'class' => Meeting::class]);
        ;

        Product::factory(4)
            ->state(new Sequence(
                ['product_type_id' => 1],
                ['product_type_id' => 2],
//                ['product_type_id' => 3]
            ))
            ->has(Category::factory(2))
            ->has(Tag::factory(4))
            ->create();

//        ProductCategory::factory(10)->for($products)->create();

//        $productCategories = ProductCategory::factory()->create();
//        Product::factory(10)->for($productCategories)->create();

//        Product::factory(6)
//            ->has(Category::factory(2))
//            ->has(Tag::factory(4))
//            ->create();

//        Order::factory(4)
//            ->has($products)
//            ->create();


        UserGroup::factory()->create(['id' => 1, 'label' => 'Basic'])
            ->create(['id' => 2, 'label' => 'Power'])
            ->create(['id' => 3, 'label' => 'Full']);
        UserRole::factory()
            ->create(['id' => 1, 'label' => 'User', 'user_group_id' => 1])
            ->create(['id' => 2, 'label' => 'Power User', 'user_group_id' => 2])
            ->create(['id' => 3, 'label' => 'Administrator', 'user_group_id' => 3]);
    }
}
