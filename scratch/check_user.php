<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->boot();

$userCount = \App\Models\User::query()->count();
echo "Total Users: " . $userCount . "\n";

$admin = \App\Models\User::query()->where('email', 'admin@siliwangi.com')->first();
if ($admin) {
    echo "Admin User exists! Email: " . $admin->email . "\n";
    echo "Password Hash: " . $admin->password . "\n";
    // Check if password matches 'password'
    if (password_verify('password', $admin->password)) {
        echo "Password matches 'password'!\n";
    } else {
        echo "Password does NOT match 'password'!\n";
    }
} else {
    echo "Admin User does NOT exist in the database!\n";
}
