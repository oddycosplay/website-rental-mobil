<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code', 'user_id', 'car_id', 'store_id', 'driver_id', 'promo_id',
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

            // 3. Sync Payment Record
            /** @var \App\Models\Payment|null $payment */
            $payment = \App\Models\Payment::query()->where('booking_id', $booking->id)->first();
            
            if (!$payment) {
                // Always ensure a Payment record exists for every booking
                $paidAmount = $booking->payment_status === 'partial' 
                    ? ($booking->dp_amount > 0 ? $booking->dp_amount : ($booking->grand_total * 0.5)) 
                    : ($booking->payment_status === 'paid' ? $booking->grand_total : 0);

                $paymentStatusMap = [
                    'unpaid' => 'pending',
                    'partial' => 'success',
                    'paid' => 'success',
                    'refunded' => 'refund',
                ];

                \App\Models\Payment::create([
                    'booking_id' => $booking->id,
                    'payment_code' => 'PAY-' . now()->format('Ym') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'payment_method' => 'manual_admin',
                    'transaction_id' => 'MANUAL-' . uniqid(),
                    'gross_amount' => $booking->grand_total,
                    'paid_amount' => $paidAmount,
                    'payment_status' => $paymentStatusMap[$booking->payment_status] ?? 'pending',
                    'payment_date' => in_array($booking->payment_status, ['partial', 'paid']) ? now() : null,
                ]);
            } else {
                // If payment exists, only update it if the Booking's payment_status was explicitly changed
                if ($booking->isDirty('payment_status')) {
                    $paymentStatusMap = [
                        'unpaid' => 'pending',
                        'partial' => 'success',
                        'paid' => 'success',
                        'refunded' => 'refund',
                    ];
                    
                    $targetStatus = $paymentStatusMap[$booking->payment_status] ?? 'pending';
                    $paidAmount = $booking->payment_status === 'partial' 
                        ? ($booking->dp_amount > 0 ? $booking->dp_amount : ($booking->grand_total * 0.5)) 
                        : ($booking->payment_status === 'paid' ? $booking->grand_total : 0);
                        
                    $payment->fill([
                        'payment_status' => $targetStatus,
                        'paid_amount' => $paidAmount,
                        'payment_method' => 'manual_admin',
                        'payment_date' => in_array($targetStatus, ['success', 'refund']) && !$payment->payment_date ? now() : $payment->payment_date,
                    ])->save();
                } else if ($booking->isDirty('grand_total')) {
                    // Update gross_amount if grand_total changes and payment is still pending
                    if ($payment->payment_status === 'pending') {
                         $payment->fill([
                             'gross_amount' => $booking->grand_total
                         ])->save();
                    }
                }
            }
        });
    }

    /**
     * Get the customer profile that made this booking.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the payment details for this booking.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the operational inspections for this booking.
     */
    public function operationals()
    {
        return $this->hasMany(Operational::class, 'booking_id');
    }

    /**
     * Get the location surveys for this booking.
     */
    public function locationSurveys()
    {
        return $this->hasMany(LocationSurvey::class, 'booking_id');
    }

    /**
     * BACKWARD COMPATIBILITY: Get the user account of the customer who made this booking.
     */
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
