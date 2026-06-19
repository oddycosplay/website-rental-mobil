<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Store;
use App\Models\Payment;
use Illuminate\Support\Str;

$car = Car::query()->first();
$branch = Store::query()->first();
$customer = Customer::query()->first();

if (!$car || !$branch || !$customer) {
    die("Error: Missing car, branch, or customer data.");
}

$bookingCode = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));

// Create booking with NO delivery and pickup services
$booking = Booking::create([
    'booking_code' => $bookingCode,
    'customer_id' => $customer->id,
    'car_id' => $car->id,
    'store_id' => $branch->id,
    'rental_type' => 'daily',
    'pickup_date' => now()->addDay(),
    'return_date' => now()->addDays(2),
    'total_day' => 1,
    'price' => $car->daily_price,
    'delivery_type' => 'none',
    'delivery_fee' => 0.0,
    'pickup_type' => 'none',
    'pickup_fee' => 0.0,
    'ojol_fee' => 0.0,
    'extra_price' => 10000.0, // admin fee
    'tax' => 12000.0,
    'discount' => 0.0,
    'grand_total' => $car->daily_price + 10000.0 + 12000.0,
    'payment_status' => 'unpaid',
    'booking_status' => 'pending',
]);

$payment = Payment::create([
    'booking_id' => $booking->id,
    'payment_code' => 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
    'gross_amount' => $booking->grand_total,
    'payment_status' => 'pending',
    'snap_token' => 'mock-snap-token-99999',
]);

echo "SUCCESS: Created pending booking with NO fees " . $booking->booking_code . " with total " . $booking->grand_total . "\n";
