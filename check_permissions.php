<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$permissions = Spatie\Permission\Models\Permission::pluck('name')->toArray();
echo "PERMISSIONS:\n";
print_r($permissions);

$roles = Spatie\Permission\Models\Role::with('permissions')->get();
echo "\nROLES:\n";
foreach ($roles as $role) {
    echo $role->name . ": " . implode(', ', $role->permissions->pluck('name')->toArray()) . "\n";
}
