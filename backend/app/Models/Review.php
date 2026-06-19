<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'user_id', 'car_id', 'rating', 'review', 'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * BACKWARD COMPATIBILITY: customer_id Accessor/Mutator
     */
    public function getCustomerIdAttribute()
    {
        return $this->user_id;
    }

    public function setCustomerIdAttribute(mixed $value)
    {
        $this->attributes['user_id'] = $value;
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
