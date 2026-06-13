<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$role = Spatie\Permission\Models\Role::where('name', 'finance')->first();
if ($role) {
    $role->givePermissionTo('view_any_payment');
    $role->givePermissionTo('view_any_expense');
    echo "Permissions given to finance.\n";
} else {
    echo "Finance role not found.\n";
}
