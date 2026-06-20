<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$users = User::all();
foreach ($users as $user) {
    echo "ID: {$user->id} | Name: {$user->name} | Email: {$user->email}\n";
    echo "  Roles: " . $user->getRoleNames()->join(', ') . "\n";
    echo "  KTP: " . ($user->ktp_image ? 'Yes' : 'No') . "\n";
    echo "  SIM: " . ($user->sim_image ? 'Yes' : 'No') . "\n";
    echo "  Avatar/Selfie: " . ($user->avatar ? 'Yes' : 'No') . "\n";
    echo "  KK: " . ($user->kk_image ? 'Yes' : 'No') . "\n";
    echo "  ID Card: " . ($user->pelajar_image ? 'Yes' : 'No') . "\n";
    echo "-----------------------------\n";
}
