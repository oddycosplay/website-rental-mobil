<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$booking = \App\Models\Booking::latest()->first();
if ($booking) {
    echo "LATEST BOOKING:\n";
    echo "Code: " . $booking->booking_code . "\n";
    echo "Total: " . $booking->grand_total . "\n";
    echo "Status: " . $booking->payment_status . "\n";
} else {
    echo "No booking found.\n";
}
