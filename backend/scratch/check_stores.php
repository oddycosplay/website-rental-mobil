<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$stores = App\Models\Store::all();
foreach ($stores as $store) {
    echo "ID: " . $store->id . "\n";
    echo "Name: " . $store->name . "\n";
    echo "Address: " . $store->address . "\n";
    echo "Google Maps: " . $store->google_maps . "\n";
    echo "---------------------------------\n";
}
