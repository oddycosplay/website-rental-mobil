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

// Create booking with Delivery, Pickup and Ojol fees
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
    'delivery_type' => 'airport',
    'delivery_fee' => 150000.0,
    'pickup_type' => 'standard',
    'pickup_fee' => 50000.0,
    'ojol_fee' => 35000.0,
    'extra_price' => 10000.0, // admin fee
    'tax' => 45000.0,
    'discount' => 20000.0,
    'grand_total' => $car->daily_price + 150000.0 + 50000.0 + 35000.0 + 10000.0 + 45000.0 - 20000.0,
    'payment_status' => 'unpaid',
    'booking_status' => 'pending',
]);

$payment = Payment::create([
    'booking_id' => $booking->id,
    'payment_code' => 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
    'gross_amount' => $booking->grand_total,
    'payment_status' => 'pending',
    'snap_token' => 'mock-snap-token-67890',
]);

echo "SUCCESS: Created pending booking with all fees " . $booking->booking_code . " with total " . $booking->grand_total . "\n";
