<?php

namespace Database\Factories\Payments;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Obligation;
use App\Models\Payments\PaymentRecord;
use App\Models\Payments\PaymentRecordType;

class PaymentRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentRecord::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'obligation_id' => Obligation::factory(),
            'payment_record_type_id' => PaymentRecordType::factory(),
        ];
    }
}
