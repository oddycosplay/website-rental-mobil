<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_employee');
    }

    public function view(User $user, Employee $model): bool
    {
        return $user->hasPermissionTo('view_employee');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_employee');
    }

    public function update(User $user, Employee $model): bool
    {
        return $user->hasPermissionTo('update_employee');
    }

    public function delete(User $user, Employee $model): bool
    {
        return $user->hasPermissionTo('delete_employee');
    }
}