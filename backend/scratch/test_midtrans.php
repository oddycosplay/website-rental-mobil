<?php

use App\Models\Car;
use App\Models\Booking;
use App\Models\Customer;
use App\Services\MidtransService;
use Illuminate\Support\Str;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $car = Car::first();
    if (!$car) {
        echo "No car found in database.\n";
        exit;
    }

    $customer = Customer::first();
    if (!$customer) {
        echo "No customer found in database.\n";
        exit;
    }

    $booking = new Booking([
        'booking_code' => 'TEST-' . strtoupper(Str::random(5)),
        'grand_total' => 500000,
        'price' => 250000,
        'total_day' => 2,
        'driver_price' => 0,
        'discount' => 0,
    ]);
    $booking->car = $car;
    $booking->customer = $customer;

    echo "Testing Midtrans Snap Token generation...\n";
    $midtrans = new MidtransService();
    $token = $midtrans->getSnapToken($booking);
    
    echo "Success! Snap Token: " . $token . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
