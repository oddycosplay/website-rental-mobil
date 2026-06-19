<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationSurvey extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::saving(function ($survey) {
            if (in_array($survey->status, ['approved', 'rejected']) && !$survey->approved_by) {
                $survey->approved_by = auth()->id() ?? 1; // Default to user ID 1 if not authenticated (e.g. in seeder)
                $survey->approved_at = now();
            }
        });
    }

    protected $fillable = [
        'store_id',
        'booking_id',
        'surveyor_name',
        'survey_date',
        'survey_type',
        'address',
        'residence_status',
        'job_status',
        'neighbor_interview',
        'photos',
        'recommendation',
        'notes',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'survey_date' => 'date',
        'residence_status' => 'array',
        'job_status' => 'array',
        'neighbor_interview' => 'array',
        'photos' => 'array',
        'approved_at' => 'datetime',
    ];

    /**
     * Relationship to Store
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    /**
     * Relationship to Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Relationship to User (Approved By Admin/Staff)
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
