<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_expense');
    }

    public function view(User $user, Expense $model): bool
    {
        return $user->hasPermissionTo('view_expense');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_expense');
    }

    public function update(User $user, Expense $model): bool
    {
        return $user->hasPermissionTo('update_expense');
    }

    public function delete(User $user, Expense $model): bool
    {
        return $user->hasPermissionTo('delete_expense');
    }
}