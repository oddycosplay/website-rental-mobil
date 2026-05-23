<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

$email = 'admin@siliwangi.com';
$user = User::where('email', $email)->first();

if (!$user) {
    echo "User $email not found.\n";
    exit;
}

echo "User found: " . $user->name . "\n";
echo "Roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";

$roles = ['super-admin', 'admin', 'owner'];
foreach ($roles as $roleName) {
    echo "Has role $roleName? " . ($user->hasRole($roleName) ? 'YES' : 'NO') . "\n";
}

if ($user->canAccessPanel(new \Filament\Panel())) {
    echo "canAccessPanel: YES\n";
} else {
    echo "canAccessPanel: NO\n";
}
