<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Force login globally
$app->booted(function () {
    $user = \App\Models\User::where('email', 'admin@siliwangi.com')->first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
    }
});

$request = \Illuminate\Http\Request::create('/admin/1', 'GET');
try {
    $response = $kernel->handle($request);
    echo "Status: " . $response->getStatusCode() . "\n";
    if ($response->isServerError() || $response->isClientError()) {
        echo "Response excerpt:\n";
        echo substr(strip_tags($response->getContent()), 0, 3000) . "\n";
    }
} catch (\Throwable $e) {
    echo "Exception: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
