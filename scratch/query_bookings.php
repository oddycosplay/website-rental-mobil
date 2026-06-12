<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$bookings = App\Models\Booking::with('payment')->get();
foreach ($bookings as $b) {
    echo "Booking Code: " . $b->booking_code . " | Status: " . $b->payment_status . " | Has Payment: " . ($b->payment ? 'Yes' : 'No') . " | Snap Token: " . ($b->payment ? $b->payment->snap_token : 'N/A') . "\n";
}
