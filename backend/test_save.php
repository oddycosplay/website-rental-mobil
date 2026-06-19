<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    \App\Models\Payment::create([
        'booking_id' => 1,
        'payment_code' => 'PAY-' . now()->format('Ym') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
        'payment_method' => 'manual_admin',
        'transaction_id' => 'MANUAL-' . uniqid(),
        'gross_amount' => 1000,
        'paid_amount' => 500,
        'payment_status' => 'success',
        'payment_date' => now(),
    ]);
    echo "OK\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
