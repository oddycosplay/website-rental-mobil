<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$payments = \App\Models\Payment::all();
echo "ALL PAYMENTS IN DATABASE:\n";
foreach ($payments as $p) {
    echo "ID: {$p->id}, Code: {$p->payment_code}, Status: {$p->payment_status}, Gross: {$p->gross_amount}, Paid: {$p->paid_amount}\n";
}
