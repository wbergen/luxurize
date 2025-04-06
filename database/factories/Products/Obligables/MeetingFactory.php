<?php

namespace Database\Factories\Products\Obligables;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Obligation;
use App\Models\Products\Obligables\;
use App\Models\Products\Obligables\Meeting;

class MeetingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meeting::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'dt' => fake()->dateTime(),
            'conference_link' => fake()->text(),
            'address_id' => ::factory(),
            'obligation_id' => Obligation::factory(),
        ];
    }
}
