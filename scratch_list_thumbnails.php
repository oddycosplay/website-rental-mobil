<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Car;

$cars = Car::all();
foreach($cars as $c) {
    echo "{$c->car_name} | {$c->thumbnail}\n";
}
