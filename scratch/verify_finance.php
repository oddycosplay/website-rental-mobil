<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

$user = User::where('email', 'admin@siliwangi.com')->first();
if (!$user) {
    $user = User::first();
}

Auth::login($user);

echo "Simulating FinanceController@payments...\n";
try {
    $request = Request::create('/dashboard/finance/payments', 'GET', [
        'search' => 'INV',
        'method' => 'bank_transfer'
    ]);
    
    $response = app(\App\Http\Controllers\Admin\FinanceController::class)->payments($request);
    echo "✅ FinanceController@payments loaded successfully!\n";
} catch (\Exception $e) {
    echo "❌ FinanceController@payments failed: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
