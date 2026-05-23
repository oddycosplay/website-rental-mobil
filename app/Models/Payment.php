<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'payment_code', 'payment_method', 'transaction_id', 
        'snap_token', 'gross_amount', 'paid_amount', 'payment_status', 
        'payment_date', 'proof_payment', 'midtrans_response', 'payment_logs'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'midtrans_response' => 'array',
        'payment_logs' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function store()
    {
        return $this->hasOneThrough(
            Store::class,
            Booking::class,
            'id',
            'id',
            'booking_id',
            'store_id'
        );
    }

    /**
     * Virtual logs relation for backwards compatibility
     */
    public function getLogsAttribute()
    {
        return collect($this->payment_logs ?? [])->map(fn ($log) => (object) [
            'id' => null,
            'payment_id' => $this->id,
            'status' => $log['status'] ?? null,
            'response' => (array) ($log['response'] ?? []),
            'created_at' => \Carbon\Carbon::parse($log['created_at'] ?? now()),
        ]);
    }
}
