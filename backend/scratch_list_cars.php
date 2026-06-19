<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Car;

$cars = Car::all();
echo "Name | Price | CallPrice | Available | Status\n";
foreach($cars as $c) {
    echo "{$c->car_name} | {$c->daily_price} | " . ($c->is_call_for_price ? 'Y' : 'N') . " | " . ($c->is_available ? 'Y' : 'N') . " | {$c->status}\n";
}
