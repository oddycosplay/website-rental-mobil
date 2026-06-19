<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Driver;
use Illuminate\Auth\Access\HandlesAuthorization;

class DriverPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_driver');
    }

    public function view(User $user, Driver $model): bool
    {
        return $user->hasPermissionTo('view_driver');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_driver');
    }

    public function update(User $user, Driver $model): bool
    {
        return $user->hasPermissionTo('update_driver');
    }

    public function delete(User $user, Driver $model): bool
    {
        return $user->hasPermissionTo('delete_driver');
    }
}