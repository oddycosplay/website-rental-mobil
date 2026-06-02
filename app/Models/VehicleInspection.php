<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleInspection extends Model
{
    use HasFactory;

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
        'inspected_at'      => 'datetime',
        'exterior'          => 'array',
        'interior'          => 'array',
        'equipment'         => 'array',
        'engine'            => 'array',
        'photos'            => 'array',
        'fuel_photos'       => 'array',
        'damage_photos'     => 'array',
        'damage_found'      => 'boolean',
        'customer_confirmed'=> 'boolean',
        'damage_cost'       => 'decimal:2',
        'dirty_fine'        => 'decimal:2',
        'fuel_fine'         => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($inspection) {
            if (!$inspection->store_id && $inspection->booking_id) {
                $inspection->store_id = $inspection->booking?->store_id;
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

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    // ── Helpers ──────────────────────────────────────────────

    public function getInspectionTypeLabelAttribute(): string
    {
        return match ($this->inspection_type) {
            'pre_rental'  => 'Pengecekan Keluar',
            'post_rental' => 'Pengecekan Masuk',
            default       => $this->inspection_type,
        };
    }

    public function getFuelLevelLabelAttribute(): string
    {
        return match ($this->fuel_level) {
            'full'          => 'Full',
            'three_quarter' => '¾',
            'half'          => '½',
            'quarter'       => '¼',
            'empty'         => 'Kosong',
            default         => $this->fuel_level,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'     => 'Draft',
            'submitted' => 'Menunggu Review',
            'approved'  => 'Disetujui',
            default     => $this->status,
        };
    }

    /**
     * Apakah ada poin yang tidak "baik"/"berfungsi" di eksterior?
     */
    public function hasExteriorIssue(): bool
    {
        if (empty($this->exterior)) {
            return false;
        }
        $ok = ['baik', 'ada', 'berfungsi'];
        foreach ($this->exterior as $value) {
            if (!in_array($value, $ok)) {
                return true;
            }
        }
        return false;
    }
}
