<?php

use App\Models\Car;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Updating Car Categories...\n";

$cars = Car::all();
$categories = ['pribadi', 'perusahaan', 'both'];

foreach ($cars as $index => $car) {
    // Distribute categories
    $cat = $categories[$index % 3];
    $car->update(['category' => $cat]);
    echo "Car ID {$car->id} ({$car->car_name}) set to category: {$cat}\n";
}

echo "Done!\n";
