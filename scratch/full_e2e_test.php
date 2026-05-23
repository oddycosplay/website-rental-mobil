<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\MidtransController;

echo "--- STARTING FULL E2E TEST ---\n";

// 1. Setup Data
$car = Car::where('is_available', true)->first();
$branch = Branch::first();
$customer = Customer::first();

if (!$car || !$branch || !$customer) {
    die("Error: Missing car, branch, or customer data.\n");
}

// 2. Create Booking
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

Payment::create([
    'booking_id' => $booking->id,
    'payment_code' => 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
    'gross_amount' => $booking->grand_total,
    'payment_status' => 'pending',
]);

echo "STEP 1: Created Booking {$booking->booking_code} (Status: {$booking->booking_status})\n";

// 3. Simulate Midtrans Callback (Settlement)
echo "STEP 2: Simulating Midtrans Callback (Settlement)...\n";

$serverKey = config('midtrans.server_key', 'SB-Mid-server-TEST');
$statusCode = '200';
$grossAmount = (string) $booking->grand_total;
$signature = hash('sha512', $booking->booking_code . $statusCode . $grossAmount . $serverKey);

$payload = [
    'order_id' => $booking->booking_code,
    'transaction_status' => 'settlement',
    'payment_type' => 'bank_transfer',
    'transaction_id' => 'TRANS-' . uniqid(),
    'gross_amount' => $grossAmount,
    'status_code' => $statusCode,
    'signature_key' => $signature,
    'transaction_time' => now()->toDateTimeString(),
];

$callbackRequest = new Request($payload);
$callbackRequest->setMethod('POST');

$controller = new MidtransController();
$response = $controller->callback($callbackRequest);

echo "STEP 3: Controller Response: " . $response->getContent() . "\n";

// 4. Verify Results
$booking->refresh();
$payment = Payment::where('booking_id', $booking->id)->latest()->first();

echo "\n--- FINAL VERIFICATION ---\n";
echo "Booking Status: " . $booking->booking_status . " (Expected: confirmed)\n";
echo "Payment Status: " . $booking->payment_status . " (Expected: paid)\n";
echo "Payment Record: " . $payment->payment_status . " (Expected: success)\n";

if ($booking->payment_status === 'paid' && $booking->booking_status === 'confirmed') {
    echo "\n✅ TEST SUCCESSFUL: Booking has been automatically confirmed!\n";
} else {
    echo "\n❌ TEST FAILED: Status mismatch.\n";
}
