<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$bookings = \App\Models\Booking::whereIn('payment_status', ['partial', 'paid'])->doesntHave('payment')->get();
foreach($bookings as $booking) {
    $paidAmount = $booking->payment_status === 'partial' 
        ? ($booking->dp_amount > 0 ? $booking->dp_amount : ($booking->grand_total * 0.5)) 
        : $booking->grand_total;

    \App\Models\Payment::create([
        'booking_id' => $booking->id,
        'payment_code' => 'PAY-' . now()->format('Ym') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
        'payment_method' => 'manual_admin',
        'transaction_id' => 'MANUAL-' . uniqid(),
        'gross_amount' => $booking->grand_total,
        'paid_amount' => $paidAmount,
        'payment_status' => 'success',
        'payment_date' => now()
    ]);
}
echo 'Synced ' . $bookings->count() . ' missing payments.' . PHP_EOL;
