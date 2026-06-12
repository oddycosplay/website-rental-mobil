<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $totalDay   = fake()->numberBetween(1, 7);
        $dailyPrice = 500000;
        $price      = $dailyPrice * $totalDay;
        $tax        = (int) ($price * 0.10);
        $grandTotal = $price + $tax;

        return [
            'booking_code'    => 'TRX-TEST-' . strtoupper(Str::random(6)),
            'customer_id'     => Customer::factory(),
            'car_id'          => Car::factory(),
            'store_id'        => Store::factory(),
            'rental_type'     => 'daily',
            'pickup_date'     => now()->addDay(),
            'return_date'     => now()->addDays(1 + $totalDay),
            'pickup_location' => 'Kantor Pusat',
            'return_location' => 'Kantor Pusat',
            'total_day'       => $totalDay,
            'price'           => $price,
            'driver_price'    => 0,
            'extra_price'     => 0,
            'late_fee'        => 0,
            'discount'        => 0,
            'tax'             => $tax,
            'grand_total'     => $grandTotal,
            'dp_amount'       => $grandTotal,
            'remaining_payment' => 0,
            'payment_status'  => 'pending',
            'booking_status'  => 'pending',
        ];
    }

    public function confirmed(): static
    {
        return $this->state(['booking_status' => 'confirmed', 'payment_status' => 'paid']);
    }

    public function paid(): static
    {
        return $this->state(['payment_status' => 'paid', 'remaining_payment' => 0]);
    }
}
