<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'booking_id'     => Booking::factory(),
            'payment_code'   => 'PAY-TEST-' . strtoupper(Str::random(6)),
            'payment_method' => fake()->randomElement(['bank_transfer', 'qris', 'gopay']),
            'transaction_id' => 'MID-' . fake()->numerify('#########'),
            'snap_token'     => null,
            'gross_amount'   => fake()->numberBetween(500000, 5000000),
            'paid_amount'    => 0,
            'payment_status' => 'pending',
            'payment_date'   => null,
        ];
    }

    public function success(int $amount = 0): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_status' => 'success',
            'paid_amount'    => $amount > 0 ? $amount : $attributes['gross_amount'],
            'payment_date'   => now(),
        ]);
    }
}
