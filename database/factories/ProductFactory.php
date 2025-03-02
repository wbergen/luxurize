<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'price' => fake()->randomFloat(2, 0, 99.99),
            'for_sale' => fake()->boolean(),
            'image' => 'https://9ee277280003abc0.nyc3.cdn.digitaloceanspaces.com/luxurize/images/layout/luxurize_logo_sm.webp',
        ];
    }
}
