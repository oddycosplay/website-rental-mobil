<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Branch;
use Illuminate\Support\Str;

$car = Car::where('is_available', true)->first();
$branch = Branch::first();
$customer = Customer::first();

if (!$car || !$branch || !$customer) {
    die("Error: Missing car, branch, or customer data.");
}

$bookingCode = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));

$booking = Booking::create([
    'booking_code' => $bookingCode,
    'customer_id' => $customer->id,
    'car_id' => $car->id,
    'branch_id' => $branch->id,
    'booking_type' => 'daily',
    'pickup_date' => now()->addDay(),
    'return_date' => now()->addDays(2),
    'total_day' => 1,
    'price' => $car->daily_price,
    'grand_total' => $car->daily_price,
    'payment_status' => 'unpaid',
    'booking_status' => 'pending',
]);

\App\Models\Payment::create([
    'booking_id' => $booking->id,
    'payment_code' => 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
    'gross_amount' => $booking->grand_total,
    'payment_status' => 'pending',
]);

echo "SUCCESS: Created booking " . $booking->booking_code . " with total " . $booking->grand_total . "\n";
