<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MidtransSimulatorController extends Controller
{
    /**
     * Simulasi callback Midtrans untuk testing lokal
     */
    public function simulate(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|exists:bookings,booking_code',
            'status' => 'required|in:settlement,pending,expire,cancel,deny',
        ]);

        $booking = Booking::where('booking_code', $request->booking_code)->first();
        
        // Data simulasi payload Midtrans
        $payload = [
            'order_id' => $booking->booking_code,
            'transaction_status' => $request->status,
            'payment_type' => 'bank_transfer',
            'transaction_id' => 'SIM-' . uniqid(),
            'gross_amount' => (string) $booking->grand_total,
            'transaction_time' => now()->toDateTimeString(),
            'fraud_status' => 'accept',
        ];

        // Generate Signature Key palsu (karena di Controller verifikasi signature)
        // signature = hash('sha512', order_id + status_code + gross_amount + server_key)
        $serverKey = config('midtrans.server_key', 'SB-Mid-server-TEST');
        $statusCode = '200'; // settlement biasanya 200
        $signature = hash('sha512', $payload['order_id'] . $statusCode . $payload['gross_amount'] . $serverKey);
        
        $payload['signature_key'] = $signature;
        $payload['status_code'] = $statusCode;

        // Kirim request ke MidtransController@callback secara internal
        // Menggunakan Http::post tidak bisa karena CSRF dan URL lokal
        // Kita panggil langsung methodnya via Request
        
        $callbackRequest = new Request($payload);
        $callbackRequest->setMethod('POST');
        
        $controller = new \App\Http\Controllers\MidtransController();
        return $controller->callback($callbackRequest);
    }

    public function view()
    {
        $bookings = Booking::latest()->take(5)->get();
        return view('admin.finance.simulator', compact('bookings'));
    }
}
