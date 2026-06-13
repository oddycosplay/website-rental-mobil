<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Store;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_store');
    }

    public function view(User $user, Store $model): bool
    {
        return $user->hasPermissionTo('view_store');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_store');
    }

    public function update(User $user, Store $model): bool
    {
        return $user->hasPermissionTo('update_store');
    }

    public function delete(User $user, Store $model): bool
    {
        return $user->hasPermissionTo('delete_store');
    }
}