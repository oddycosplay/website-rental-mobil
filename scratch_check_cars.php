<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CarType;
use App\Models\Car;

echo "Car Types:\n";
print_r(CarType::pluck('name')->toArray());

echo "\nCar Categories (checking if column exists):\n";
try {
    $categories = Car::distinct()->pluck('category')->toArray();
    print_r($categories);
} catch (\Exception $e) {
    echo "Column 'category' does not exist in cars table.\n";
}
