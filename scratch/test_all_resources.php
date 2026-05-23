<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Force login globally
$app->booted(function () {
    $user = \App\Models\User::query()->where('email', 'admin@siliwangi.com')->first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
    }
});

$routes = [
    'Dashboard' => '/admin/1',
    'Cars Resource' => '/admin/1/cars/cars',
    'Car Brands Resource' => '/admin/1/car-brands/car-brands',
    'Car Types Resource' => '/admin/1/car-types/car-types',
    'Drivers Resource' => '/admin/1/drivers/drivers',
    'Stores Resource' => '/admin/1/stores/stores',
    'Bookings Resource' => '/admin/1/bookings/bookings',
    'Payments Resource' => '/admin/1/payments/payments',
    'Users Resource' => '/admin/1/users/users',
];

echo "Checking all Admin Dashboard pages and resources:\n";
echo str_repeat('-', 60) . "\n";

foreach ($routes as $name => $path) {
    $request = \Illuminate\Http\Request::create($path, 'GET');
    try {
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        echo sprintf("%-25s | Path: %-30s | Status: %d\n", $name, $path, $status);
        if ($status !== 200) {
            echo "Error details: " . substr(strip_tags($response->getContent()), 0, 500) . "\n\n";
        }
    } catch (\Throwable $e) {
        echo sprintf("%-25s | Path: %-30s | CRASHED: %s\n", $name, $path, $e->getMessage());
        echo "Trace:\n" . $e->getTraceAsString() . "\n\n";
    }
}
