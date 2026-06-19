<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$bookingCode = 'INV-20260612-HHVWA';
echo "Before test:\n";
$b = App\Models\Booking::where('booking_code', $bookingCode)->with('payment')->first();
echo "Has Payment: " . ($b->payment ? 'Yes' : 'No') . "\n";

echo "Simulating controller call:\n";
$controller = new App\Http\Controllers\Admin\BookingController();
$request = Illuminate\Http\Request::create("/invoice/$bookingCode", 'GET');
$response = $controller->invoice($request, $bookingCode);

echo "After test:\n";
$b = App\Models\Booking::where('booking_code', $bookingCode)->with('payment')->first();
echo "Has Payment: " . ($b->payment ? 'Yes' : 'No') . "\n";
echo "Snap Token: " . ($b->payment ? $b->payment->snap_token : 'N/A') . "\n";
