<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$models = [
    'Booking', 'Car', 'Customer', 'Driver', 'Employee',
    'Expense', 'ExpenseCategory', 'Operational', 'Payment', 'Store', 'Role'
];

$policyDir = __DIR__ . '/app/Policies';
if (!is_dir($policyDir)) {
    mkdir($policyDir, 0755, true);
}

foreach ($models as $model) {
    $policyName = "{$model}Policy";
    $modelClass = $model === 'Role' ? 'Spatie\Permission\Models\Role' : "App\Models\\{$model}";
    $permissionPrefix = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model));

    $content = <<<PHP
<?php

namespace App\Policies;

use App\Models\User;
use {$modelClass};
use Illuminate\Auth\Access\HandlesAuthorization;

class {$policyName}
{
    use HandlesAuthorization;

    public function viewAny(User \$user): bool
    {
        return \$user->hasPermissionTo('view_any_{$permissionPrefix}');
    }

    public function view(User \$user, {$model} \$model): bool
    {
        return \$user->hasPermissionTo('view_{$permissionPrefix}');
    }

    public function create(User \$user): bool
    {
        return \$user->hasPermissionTo('create_{$permissionPrefix}');
    }

    public function update(User \$user, {$model} \$model): bool
    {
        return \$user->hasPermissionTo('update_{$permissionPrefix}');
    }

    public function delete(User \$user, {$model} \$model): bool
    {
        return \$user->hasPermissionTo('delete_{$permissionPrefix}');
    }
}
PHP;

    file_put_contents("{$policyDir}/{$policyName}.php", $content);
    echo "Created {$policyName}\n";
}
