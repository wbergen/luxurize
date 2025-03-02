<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;

class ReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'stars' => fake()->numberBetween(-10000, 10000),
            'title' => fake()->sentence(4),
            'content' => fake()->paragraphs(3, true),
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
        ];
    }
}
