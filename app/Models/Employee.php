<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_id',
        'name',
        'email',
        'phone',
        'nip',
        'position',
        'is_active',
    ];

    /**
     * Get the user account linked to this employee profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the store (branch) linked to this employee.
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
