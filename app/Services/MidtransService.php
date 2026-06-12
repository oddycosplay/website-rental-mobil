<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production'); // Berilai false
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Disable SSL verification for development/sandbox to avoid local curl certificate file issues
        if (!config('midtrans.is_production')) {
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => [],
            ];
        }
    }

    public function getSnapToken(Booking $booking)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $booking->booking_code.'-'.Str::random(5),
                'gross_amount' => (int) $booking->grand_total,
            ],
            'customer_details' => [
                'first_name' => $booking->customer->name,
                'email' => $booking->customer->email,
                'phone' => $booking->customer->phone,
            ],
            'item_details' => [
                [
                    'id' => $booking->car_id,
                    'price' => (int) ($booking->price * $booking->total_day),
                    'quantity' => 1,
                    'name' => 'Sewa '.$booking->car->car_name.' ('.$booking->total_day.' hari)',
                ],
            ],
        ];

        if ($booking->driver_price > 0) {
            $params['item_details'][] = [
                'id' => 'DRIVER',
                'price' => (int) $booking->driver_price,
                'quantity' => 1,
                'name' => 'Jasa Driver Profesional',
            ];
        }

        if ($booking->discount > 0) {
            $params['item_details'][] = [
                'id' => 'DISCOUNT',
                'price' => (int) -$booking->discount,
                'quantity' => 1,
                'name' => 'Potongan Promo',
            ];
        }

        return Snap::getSnapToken($params);
    }

    public function checkStatus(string $orderId)
    {
        return Transaction::status($orderId);
    }
}
