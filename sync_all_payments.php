<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$bookings = \App\Models\Booking::doesntHave('payment')->get();
$count = 0;
foreach($bookings as $booking) {
    $paidAmount = $booking->payment_status === 'partial' 
        ? ($booking->dp_amount > 0 ? $booking->dp_amount : ($booking->grand_total * 0.5)) 
        : ($booking->payment_status === 'paid' ? $booking->grand_total : 0);

    $paymentStatusMap = [
        'unpaid' => 'pending',
        'partial' => 'success',
        'paid' => 'success',
        'refunded' => 'refund',
    ];

    \App\Models\Payment::create([
        'booking_id' => $booking->id,
        'payment_code' => 'PAY-' . now()->format('Ym') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
        'payment_method' => 'manual_admin',
        'transaction_id' => 'MANUAL-' . uniqid(),
        'gross_amount' => $booking->grand_total,
        'paid_amount' => $paidAmount,
        'payment_status' => $paymentStatusMap[$booking->payment_status] ?? 'pending',
        'payment_date' => in_array($booking->payment_status, ['partial', 'paid']) ? now() : null,
    ]);
    $count++;
}
echo 'Synced ' . $count . ' missing payments (including pending ones).' . PHP_EOL;
