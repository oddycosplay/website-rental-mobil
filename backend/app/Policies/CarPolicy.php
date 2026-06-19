<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Car;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_car');
    }

    public function view(User $user, Car $model): bool
    {
        return $user->hasPermissionTo('view_car');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_car');
    }

    public function update(User $user, Car $model): bool
    {
        return $user->hasPermissionTo('update_car');
    }

    public function delete(User $user, Car $model): bool
    {
        return $user->hasPermissionTo('delete_car');
    }
}