<?php

namespace Database\Factories\Products;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Products\Product;
use App\Models\Products\ProductType;

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
            'price' => fake()->randomFloat(2, 0, 19.99),
            'for_sale' => fake()->word(),
            'image' => fake()->regexify('[A-Za-z0-9]{256}'),
            'product_type_id' => ProductType::factory(),
        ];
    }
}
