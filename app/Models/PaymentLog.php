<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = [
        'payment_id', 'response', 'status'
    ];

    protected $casts = [
        'response' => 'array',
    ];

    // Disable table interactions
    public $timestamps = false;
    protected $table = 'payments';

    /**
     * Intercept Save to write directly to Payment's JSON payment_logs column
     */
    public function save(array $options = [])
    {
        $payment = Payment::find($this->payment_id);
        if ($payment) {
            $logs = $payment->payment_logs ?? [];
            $logs[] = [
                'status' => $this->status,
                'response' => $this->response,
                'created_at' => now()->toDateTimeString(),
            ];
            $payment->payment_logs = $logs;
            $payment->save();
        }
        return true;
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
