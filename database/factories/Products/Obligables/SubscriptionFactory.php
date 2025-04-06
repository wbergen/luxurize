<?php

namespace Database\Factories\Products\Obligables;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Obligation;
use App\Models\Products\Obligables\Subscription;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'active' => fake()->word(),
            'valid_until' => fake()->dateTime(),
            'obligation_id' => Obligation::factory(),
        ];
    }
}
