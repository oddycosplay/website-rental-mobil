<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;

$bookings = Booking::all();
echo "ID | Code | Total | Status\n";
foreach($bookings as $b) {
    echo "{$b->id} | {$b->booking_code} | {$b->grand_total} | {$b->status}\n";
}
