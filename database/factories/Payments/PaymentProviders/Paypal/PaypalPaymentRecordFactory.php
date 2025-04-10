<?php

namespace Database\Factories\Payments\PaymentProviders\Paypal;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Payments\PaymentProviders\Paypal\PaymentRecord;
use App\Models\Payments\PaymentProviders\Paypal\PaypalPaymentRecord;

class PaypalPaymentRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaypalPaymentRecord::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'provider_id' => fake()->regexify('[A-Za-z0-9]{128}'),
            'provider_status' => fake()->regexify('[A-Za-z0-9]{16}'),
            'provider_cut' => fake()->randomFloat(2, 0, 999.99),
            'provider_gross' => fake()->randomFloat(2, 0, 9999999.99),
            'provider_net' => fake()->randomFloat(2, 0, 9999999.99),
            'provider_payment_id' => fake()->regexify('[A-Za-z0-9]{128}'),
            'payer_id' => fake()->regexify('[A-Za-z0-9]{32}'),
            'payer_email' => fake()->regexify('[A-Za-z0-9]{128}'),
            'payer_name' => fake()->regexify('[A-Za-z0-9]{128}'),
            'payer_last_name' => fake()->regexify('[A-Za-z0-9]{128}'),
            'payment_record_id' => PaymentRecord::factory(),
        ];
    }
}
