<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'store_id', 'name', 'phone', 'address', 'license_number', 'photo', 'daily_fee', 'rating', 'status', 'is_available', 'branch_id'
    ];

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

    public function setBranchIdAttribute($value)
    {
        $this->attributes['store_id'] = $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(DriverSchedule::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailableForDates($query, $startDate, $endDate)
    {
        return $query->where('status', 'available')
            ->where('is_available', true)
            ->whereDoesntHave('schedules', function ($q) use ($startDate, $endDate) {
                $q->where(function ($inner) use ($startDate, $endDate) {
                    $inner->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($superInner) use ($startDate, $endDate) {
                            $superInner->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                        });
                })->whereNotIn('status', ['cancelled', 'completed']);
            });
    }

    public function getIsBusyAttribute()
    {
        return $this->schedules()
            ->where('status', 'ongoing')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->exists();
    }
}
