<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ObligationStatus;

class ObligationStatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ObligationStatus::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'label' => fake()->regexify('[A-Za-z0-9]{64}'),
        ];
    }
}
