<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_payment');
    }

    public function view(User $user, Payment $model): bool
    {
        return $user->hasPermissionTo('view_payment');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_payment');
    }

    public function update(User $user, Payment $model): bool
    {
        return $user->hasPermissionTo('update_payment');
    }

    public function delete(User $user, Payment $model): bool
    {
        return $user->hasPermissionTo('delete_payment');
    }
}