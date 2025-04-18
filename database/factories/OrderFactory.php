<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\;
use App\Models\Order;
use App\Models\User;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'obligation_id' => ::factory(),
            'payment_record_id' => ::factory(),
            'price' => fake()->randomFloat(2, 0, 999999.99),
            'user_id' => User::factory(),
        ];
    }
}
