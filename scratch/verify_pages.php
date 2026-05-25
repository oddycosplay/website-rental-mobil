<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

$user = User::where('email', 'admin@siliwangi.com')->first();
if (!$user) {
    echo "Admin user not found. Preparing admin user...\n";
    $user = User::firstOrCreate(
        ['email' => 'admin@siliwangi.com'],
        [
            'name' => 'Super Admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]
    );
}

Auth::login($user);

echo "Simulating Dashboard Controller request...\n";
try {
    $response = app(\App\Http\Controllers\Admin\DashboardController::class)->index();
    echo "✅ Dashboard Controller Index loaded successfully!\n";
} catch (\Exception $e) {
    echo "❌ Dashboard Controller Index failed: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\nSimulating CarSchedule Controller request...\n";
try {
    $response = app(\App\Http\Controllers\Admin\CarScheduleController::class)->index();
    echo "✅ CarSchedule Controller Index loaded successfully!\n";
} catch (\Exception $e) {
    echo "❌ CarSchedule Controller Index failed: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
