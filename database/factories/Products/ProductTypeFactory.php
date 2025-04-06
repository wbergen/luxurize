<?php

namespace Database\Factories\Products;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Products\ProductType;

class ProductTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductType::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'label' => fake()->regexify('[A-Za-z0-9]{16}'),
            'class' => fake()->regexify('[A-Za-z0-9]{256}'),
        ];
    }
}
