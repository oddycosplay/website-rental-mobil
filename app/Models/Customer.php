<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'nik',
        'sim_number',
        'ktp_image',
        'sim_image',
        'selfie_image',
        'ktp_path',
        'sim_path',
        'no_kk',
        'kk_photo',
        'nip_nim',
        'id_card_photo',
        'pekerjaan',
        'customer_status',
        'address',
        'date_of_birth',
        'is_active',
    ];

    /**
     * Get the user account linked to this customer profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the bookings made by this customer.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the reviews written by this customer.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
