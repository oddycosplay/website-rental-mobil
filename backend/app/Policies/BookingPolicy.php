<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_booking');
    }

    public function view(User $user, Booking $model): bool
    {
        return $user->hasPermissionTo('view_booking');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_booking');
    }

    public function update(User $user, Booking $model): bool
    {
        return $user->hasPermissionTo('update_booking');
    }

    public function delete(User $user, Booking $model): bool
    {
        return $user->hasPermissionTo('delete_booking');
    }
}