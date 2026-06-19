<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::saved(function ($car) {
            \Illuminate\Support\Facades\Cache::forget("car_detail_{$car->slug}");
        });

        static::deleted(function ($car) {
            \Illuminate\Support\Facades\Cache::forget("car_detail_{$car->slug}");
        });
    }

    protected $fillable = [
        'store_id',
        'branch_id',
        'category',
        'car_name',
        'slug',
        'plate_number',
        'stnk_expiry',
        'tax_expiry',
        'year',
        'color',
        'seat',
        'transmission',
        'fuel_type',
        'mileage',
        'daily_price',
        'driver_daily_price',
        'is_call_for_price',
        'monthly_price',
        'late_fee',
        'thumbnail',
        'description',
        'status',
        'is_available',
        'featured',
        
        // Consolidated Brand
        'brand_name',
        'brand_slug',
        'brand_logo',

        // Consolidated Type
        'type_name',
        'type_description',

        // Consolidated Images JSON
        'images',

        // Consolidated GPS Location
        'latitude',
        'longitude',
        'speed',
        'location_address',
        'location_raw_data',

        // Consolidated Maintenance JSON
        'maintenances',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'featured' => 'boolean',
        'is_call_for_price' => 'boolean',
        'images' => 'array',
        'location_raw_data' => 'array',
        'maintenances' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'speed' => 'decimal:2',
    ];

    /**
     * Relationship to Store
     */
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

    /**
     * Relationship to Bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Relationship to Operationals (Inspeksi Kendaraan)
     */
    public function operationals()
    {
        return $this->hasMany(Operational::class, 'car_id');
    }

    /**
     * Relationship to Reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * BACKWARD COMPATIBILITY: Brand Accessor
     * Simulates the $car->brand relation object.
     */
    public function getBrandAttribute()
    {
        return (object) [
            'id' => null,
            'name' => $this->brand_name,
            'slug' => $this->brand_slug,
            'logo' => $this->brand_logo,
        ];
    }

    /**
     * BACKWARD COMPATIBILITY: Type Accessor
     * Simulates the $car->type relation object.
     */
    public function getTypeAttribute()
    {
        return (object) [
            'id' => null,
            'name' => $this->type_name,
            'description' => $this->type_description,
        ];
    }

    /**
     * BACKWARD COMPATIBILITY: Latest Location Accessor
     * Simulates $car->latestLocation.
     */
    public function getLatestLocationAttribute()
    {
        return (object) [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'speed' => $this->speed,
            'location_address' => $this->location_address,
            'created_at' => $this->updated_at,
        ];
    }

    /**
     * BACKWARD COMPATIBILITY: Stock Accessor
     * Simulates stock quantity which is always 1 physical car per record.
     */
    public function getStockAttribute()
    {
        return 1;
    }

    public function setStockAttribute($value)
    {
        // Ignore setting since each record is a unique physical car
    }
}
