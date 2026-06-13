<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_customer');
    }

    public function view(User $user, Customer $model): bool
    {
        return $user->hasPermissionTo('view_customer');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_customer');
    }

    public function update(User $user, Customer $model): bool
    {
        return $user->hasPermissionTo('update_customer');
    }

    public function delete(User $user, Customer $model): bool
    {
        return $user->hasPermissionTo('delete_customer');
    }
}