<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ExpenseCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpenseCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_expense_category');
    }

    public function view(User $user, ExpenseCategory $model): bool
    {
        return $user->hasPermissionTo('view_expense_category');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_expense_category');
    }

    public function update(User $user, ExpenseCategory $model): bool
    {
        return $user->hasPermissionTo('update_expense_category');
    }

    public function delete(User $user, ExpenseCategory $model): bool
    {
        return $user->hasPermissionTo('delete_expense_category');
    }
}