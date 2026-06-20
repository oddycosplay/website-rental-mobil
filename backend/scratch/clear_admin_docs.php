<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

$user = User::query()->find(1);
if ($user) {
    $user->ktp_image = null;
    $user->sim_image = null;
    $user->avatar = null;
    $user->kk_image = null;
    $user->pelajar_image = null;
    $user->save();
    echo "Super Admin documents cleared!\n";
} else {
    echo "Super Admin not found.\n";
}
