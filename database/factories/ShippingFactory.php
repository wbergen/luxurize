<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Shipping;
use App\Models\User;

class ShippingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shipping::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'address' => fake()->regexify('[A-Za-z0-9]{256}'),
            'address_two' => fake()->regexify('[A-Za-z0-9]{256}'),
            'city' => fake()->city(),
            'state' => fake()->regexify('[A-Za-z0-9]{16}'),
            'zip' => fake()->postcode(),
            'country' => fake()->country(),
            'user_id' => User::factory(),
        ];
    }
}
