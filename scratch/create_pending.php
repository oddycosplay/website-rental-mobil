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
    'grand_total' => $car->daily_price + 50000 + 10000,
    'extra_price' => 60000,
    'payment_status' => 'unpaid',
    'booking_status' => 'pending',
]);

$payment = Payment::create([
    'booking_id' => $booking->id,
    'payment_code' => 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
    'gross_amount' => $booking->grand_total,
    'payment_status' => 'pending',
    'snap_token' => 'mock-snap-token-12345',
]);

echo "SUCCESS: Created pending booking " . $booking->booking_code . " with snap token " . $payment->snap_token . "\n";
