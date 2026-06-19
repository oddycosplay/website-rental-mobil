<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operational extends Model
{
    use HasFactory;

    protected $table = 'operationals';

    protected $fillable = [
        'store_id',
        'booking_id',
        'car_id',
        'inspector_name',
        'inspection_type',
        'inspected_at',
        'odometer_km',
        'fuel_level',
        'exterior',
        'interior',
        'equipment',
        'engine',
        'photos',
        'fuel_photos',
        'damage_found',
        'damage_description',
        'damage_cost',
        'dirty_fine',
        'fuel_fine',
        'damage_photos',
        'customer_confirmed',
        'customer_note',
        'notes',
        'status',
    ];

    protected $casts = [
        'inspected_at' => 'datetime',
        'odometer_km' => 'integer',
        'exterior' => 'array',
        'interior' => 'array',
        'equipment' => 'array',
        'engine' => 'array',
        'photos' => 'array',
        'fuel_photos' => 'array',
        'damage_found' => 'boolean',
        'damage_cost' => 'decimal:2',
        'dirty_fine' => 'decimal:2',
        'fuel_fine' => 'decimal:2',
        'damage_photos' => 'array',
        'customer_confirmed' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
