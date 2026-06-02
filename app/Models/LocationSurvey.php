<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationSurvey extends Model
{
    use HasFactory;

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
        'survey_date'        => 'date',
        'residence_status'   => 'array',
        'job_status'         => 'array',
        'neighbor_interview' => 'array',
        'photos'             => 'array',
        'approved_at'        => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($survey) {
            if (!$survey->store_id && $survey->booking_id) {
                $survey->store_id = $survey->booking?->store_id;
            }
        });

        static::saved(function ($survey) {
            if ($survey->status === 'rejected' || $survey->recommendation === 'tidak_layak') {
                if ($survey->booking && $survey->booking->customer) {
                    $survey->booking->customer->update([
                        'customer_status' => 'blacklist',
                        'is_active' => false,
                    ]);
                }
                
                if ($survey->booking && $survey->booking->booking_status !== 'cancelled') {
                    $survey->booking->update([
                        'booking_status' => 'cancelled',
                        'notes' => 'Survey ditolak karena renter dinilai TIDAK LAYAK. Akun di-blacklist dan proses refund dipicu otomatis.',
                    ]);
                }
            }
        });
    }

    // ── Relations ────────────────────────────────────────────

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Helpers ──────────────────────────────────────────────

    public function getRecommendationLabelAttribute(): string
    {
        return match ($this->recommendation) {
            'layak'               => 'Layak',
            'tidak_layak'         => 'Tidak Layak',
            'layak_dengan_catatan'=> 'Layak dg Catatan',
            default               => '-',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'     => 'Draft',
            'submitted' => 'Menunggu Approve',
            'approved'  => 'Disetujui',
            'rejected'  => 'Ditolak',
            default     => $this->status,
        };
    }
}
