<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['super-admin', 'admin', 'owner', 'finance', 'operasional']);
    }

    public function getTenants(Panel $panel): Collection
    {
        if ($this->hasRole('super-admin')) {
            return Store::all();
        }
        
        return $this->store ? collect([$this->store]) : collect();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->hasRole('super-admin')) {
            return true;
        }

        return $this->store_id === $tenant->id;
    }
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'password',
        'store_id',
        'role',
        'is_active',
    ];

    /**
     * Get the store this user belongs to.
     */
    public function store()
    {
        return $this->belongsTo(\App\Models\Store::class, 'store_id');
    }

    /**
     * BACKWARD COMPATIBILITY: Get the branch (maps to store) this user belongs to.
     */
    public function branch()
    {
        return $this->belongsTo(\App\Models\Store::class, 'store_id');
    }

    /**
     * BACKWARD COMPATIBILITY: Branch ID Accessor/Mutator
     */
    public function getBranchIdAttribute()
    {
        return $this->store_id;
    }

    public function setBranchIdAttribute(mixed $value)
    {
        $this->attributes['store_id'] = $value;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the customer profile linked to this user account.
     */
    public function customer()
    {
        return $this->hasOne(\App\Models\Customer::class);
    }

    /**
     * Get all bookings for this user through their customer profile.
     */
    public function bookings()
    {
        return $this->hasManyThrough(
            \App\Models\Booking::class,
            \App\Models\Customer::class,
            'user_id',
            'customer_id',
            'id',
            'id'
        );
    }

    /**
     * Get the employee profile linked to this user account.
     */
    public function employee()
    {
        return $this->hasOne(\App\Models\Employee::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
