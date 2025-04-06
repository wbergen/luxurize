<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\;
use App\Models\Obligation;
use App\Models\ObligationStatus;
use App\Models\Order;
use App\Models\PaymentRecord;
use App\Models\User;

class ObligationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Obligation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'price' => fake()->randomFloat(2, 0, 999999.99),
            'promotion_id' => ::factory(),
            'obligation_status_id' => ObligationStatus::factory(),
            'user_id' => User::factory(),
            'order_id' => Order::factory(),
            'payment_record_id' => PaymentRecord::factory(),
        ];
    }
}
