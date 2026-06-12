<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code', 'customer_id', 'car_id', 'store_id', 'driver_id', 'promo_id',
        'rental_type', 'rental_category', 'area', 'with_driver', 'driver_name', 'pickup_date', 'return_date', 'pickup_location', 'return_location',
        'total_day', 'price', 'driver_price', 'extra_price', 'late_fee', 'discount', 'tax',
        'grand_total', 'dp_amount', 'remaining_payment', 'payment_status', 'booking_status', 'notes', 'expired_at',
        'guest_token', 'guest_name', 'guest_email', 'guest_phone', 'ktp_path', 'sim_path',
        'delivery_type', 'pickup_type', 'delivery_fee', 'pickup_fee', 'ojol_fee'
    ];

    protected $casts = [
        'pickup_date' => 'datetime',
        'return_date' => 'datetime',
        'expired_at' => 'datetime',
        'with_driver' => 'boolean',
        'delivery_fee' => 'float',
        'pickup_fee' => 'float',
        'ojol_fee' => 'float',
    ];

    protected static function booted()
    {
        static::saved(function ($booking) {
            // 1. Update Car Status
            if (in_array($booking->booking_status, ['confirmed', 'ongoing'])) {
                if ($booking->car) {
                    $booking->car->update(['status' => 'rented']);
                }
            } elseif (in_array($booking->booking_status, ['completed', 'cancelled', 'expired'])) {
                if ($booking->car) {
                    if ($booking->car->status !== 'maintenance') {
                        $booking->car->update(['status' => 'available']);
                    }
                }

                // Jika status selesai, hitung denda final jika telat
                if ($booking->booking_status === 'completed') {
                    $now = Carbon::now();
                    if ($now->greaterThan($booking->return_date)) {
                        $diffHours = $now->diffInHours($booking->return_date);
                        $penaltyPerHour = $booking->car->late_fee ?? 50000;
                        $finalLateFee = $diffHours * $penaltyPerHour;

                        // Gunakan query builder agar tidak mentrigger event saved lagi (mencegah loop)
                        $booking->newQuery()->where('id', $booking->id)->update([
                            'late_fee' => $finalLateFee,
                            'grand_total' => $booking->grand_total + $finalLateFee,
                            'remaining_payment' => $booking->remaining_payment + $finalLateFee,
                        ]);
                    }
                }
            }

            // 2. Update Driver Schedule
            if ($booking->driver_id) {
                $statusMap = [
                    'pending' => 'scheduled',
                    'confirmed' => 'scheduled',
                    'ongoing' => 'ongoing',
                    'completed' => 'completed',
                    'cancelled' => 'cancelled',
                    'expired' => 'cancelled',
                ];

                DriverSchedule::query()->updateOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'driver_id' => $booking->driver_id,
                        'start_date' => $booking->pickup_date,
                        'end_date' => $booking->return_date,
                        'status' => $statusMap[$booking->booking_status] ?? 'scheduled',
                    ]
                );
            } else {
                DriverSchedule::query()->where('booking_id', $booking->id)->delete();
            }
        });
    }

    /**
     * Get the customer profile that made this booking.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the payment details for this booking.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * BACKWARD COMPATIBILITY: Get the user account of the customer who made this booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id'); // Fallback or direct link
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
 
    /**
     * BACKWARD COMPATIBILITY: Relationship to Branch (maps to Store)
     */
    public function branch()
    {
        return $this->belongsTo(Store::class, 'store_id');
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

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function locationSurveys()
    {
        return $this->hasMany(LocationSurvey::class);
    }

    public function operationals()
    {
        return $this->hasMany(Operational::class);
    }

    /**
     * BACKWARD COMPATIBILITY: booking_type Accessor & Mutator
     */
    public function getBookingTypeAttribute()
    {
        return $this->rental_type;
    }

    public function setBookingTypeAttribute(mixed $value)
    {
        $this->attributes['rental_type'] = $value;
    }

    /**
     * BACKWARD COMPATIBILITY: booking_category Accessor & Mutator
     */
    public function getBookingCategoryAttribute()
    {
        return $this->rental_type === 'monthly' ? 'corporate' : 'pribadi';
    }

    public function setBookingCategoryAttribute(mixed $value)
    {
        $this->attributes['rental_type'] = $value === 'corporate' ? 'monthly' : 'daily';
    }
}
