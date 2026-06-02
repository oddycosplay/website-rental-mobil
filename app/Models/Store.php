<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Store extends Model
{
    use HasFactory;
    
    protected static function booted()
    {
        static::saved(function() {
            \Illuminate\Support\Facades\Cache::forget('stores_list');
            \Illuminate\Support\Facades\Cache::forget('branches_list');
        });
        static::deleted(function() {
            \Illuminate\Support\Facades\Cache::forget('stores_list');
            \Illuminate\Support\Facades\Cache::forget('branches_list');
        });
    }
 
    protected $fillable = [
        'name',
        'slug',
        'phone',
        'email',
        'address',
        'city',
        'province',
        'google_maps',
        'status'
    ];
 
    public function cars()
    {
        return $this->hasMany(Car::class, 'store_id');
    }
 
    public function drivers()
    {
        return $this->hasMany(Driver::class, 'store_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'store_id');
    }

    public function locationSurveys()
    {
        return $this->hasMany(LocationSurvey::class, 'store_id');
    }

    public function vehicleInspections()
    {
        return $this->hasMany(VehicleInspection::class, 'store_id');
    }
}
