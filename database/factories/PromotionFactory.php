<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Promotion;

class PromotionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Promotion::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'discount_percent' => fake()->randomFloat(4, 0, .9999),
            'discount_value' => fake()->randomFloat(2, 0, 999999.99),
            'order_id' => Order::factory(),
        ];
    }
}
