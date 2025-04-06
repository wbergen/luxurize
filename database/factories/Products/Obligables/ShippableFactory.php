<?php

namespace Database\Factories\Products\Obligables;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Obligation;
use App\Models\Products\Obligables\Shippable;
use App\Models\Shipping;

class ShippableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shippable::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'obligation_id' => Obligation::factory(),
            'shipping_id' => Shipping::factory(),
        ];
    }
}
