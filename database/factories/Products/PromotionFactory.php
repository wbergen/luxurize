<?php

namespace Database\Factories\Products;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Obligation;
use App\Models\Products\Promotion;

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
            'obligation_id' => Obligation::factory(),
        ];
    }
}
