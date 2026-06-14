<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Str;

class MidtransDemoService
{
    /**
     * Generate a demo Snap token for testing
     * This is used when MIDTRANS_IS_PRODUCTION=false
     */
    public function generateDemoSnapToken(Booking $booking): string
    {
        $orderId = $booking->booking_code.'-'.Str::random(5);
        
        // Generate a realistic looking token
        // Format: snap-<timestamp>-<random>
        $timestamp = now()->getTimestampMs();
        $randomPart = strtoupper(Str::random(20));
        
        $token = "snap-{$timestamp}-{$randomPart}";
        
        return $token;
    }

    /**
     * Generate mock Midtrans callback payload for testing
     */
    public function generateMockCallbackPayload(
        string $orderId,
        int $grossAmount,
        string $status = 'settlement',
        ?string $paymentMethod = null
    ): array {
        $serverKey = config('midtrans.server_key');
        $statusCode = $this->getStatusCode($status);
        
        // Generate signature
        $signature = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
        
        return [
            'order_id' => $orderId,
            'merchant_id' => config('midtrans.merchant_id'),
            'gross_amount' => $grossAmount,
            'payment_type' => $paymentMethod ?? 'credit_card',
            'currency' => 'IDR',
            'transaction_status' => $status,
            'transaction_time' => now()->toIso8601String(),
            'transaction_id' => 'DEMO-'.Str::random(16),
            'status_code' => $statusCode,
            'signature_key' => $signature,
            'bank' => null,
            'eci' => '05',
            'channel_response_code' => '00',
            'channel_response_message' => 'Approved',
            'card_number' => '4111111111111111', // Demo card
            'card_exp_month' => '12',
            'card_exp_year' => now()->addYear()->format('Y'),
            'card_issuer' => 'DEMO BANK',
            'card_brand' => 'VISA',
            'three_ds_version' => '2',
            'three_ds_eci' => '05',
            'settlement_time' => $status === 'settlement' ? now()->toIso8601String() : null,
        ];
    }

    /**
     * Get Midtrans status code from transaction status
     */
    private function getStatusCode(string $status): string
    {
        return match($status) {
            'settlement' => '200',
            'capture' => '200',
            'pending' => '201',
            'deny' => '202',
            'cancel' => '202',
            'expire' => '407',
            default => '200',
        };
    }

    /**
     * Get all available demo payment statuses
     */
    public function getAvailableStatuses(): array
    {
        return [
            'settlement' => 'Payment successful (Capture/Settlement)',
            'pending' => 'Payment pending (waiting for bank confirmation)',
            'deny' => 'Payment denied',
            'expire' => 'Payment expired',
            'cancel' => 'Payment cancelled',
        ];
    }

    /**
     * Get all available demo payment methods
     */
    public function getAvailablePaymentMethods(): array
    {
        return [
            'credit_card' => 'Credit Card',
            'bank_transfer' => 'Bank Transfer',
            'gopay' => 'GoPay',
            'permata' => 'Permata VA',
            'bca_va' => 'BCA VA',
            'bni_va' => 'BNI VA',
            'cimb_va' => 'CIMB VA',
            'echannel' => 'eChannel',
            'indomaret' => 'Indomaret',
            'alfamart' => 'Alfamart',
        ];
    }
}
