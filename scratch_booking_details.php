<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;

$b = Booking::find(5);
if ($b) {
    echo "Booking ID: {$b->id}\n";
    echo "Store ID: {$b->store_id}\n";
    echo "Store Name: " . ($b->store ? $b->store->name : 'N/A') . "\n";
}
