<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Operational;
use Illuminate\Auth\Access\HandlesAuthorization;

class OperationalPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_operational');
    }

    public function view(User $user, Operational $model): bool
    {
        return $user->hasPermissionTo('view_operational');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_operational');
    }

    public function update(User $user, Operational $model): bool
    {
        return $user->hasPermissionTo('update_operational');
    }

    public function delete(User $user, Operational $model): bool
    {
        return $user->hasPermissionTo('delete_operational');
    }
}