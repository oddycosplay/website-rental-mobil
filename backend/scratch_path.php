<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
echo "Base Path: " . $request->getBasePath() . "\n";
echo "Base URL: " . $request->getBaseUrl() . "\n";
