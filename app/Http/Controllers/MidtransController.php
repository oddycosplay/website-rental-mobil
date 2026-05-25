<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\PaymentLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            // Support console E2E testing by mocking the Notification object
            if (app()->runningInConsole() || $request->header('X-Test-Callback') === 'true') {
                $notification = (object) $request->all();
            } else {
                $notification = new Notification();
            }
            
            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $grossAmount = $notification->gross_amount;
            $statusCode = $notification->status_code;
            $signatureKey = $notification->signature_key;

            // 1. Signature Verification
            $serverKey = config('midtrans.server_key');
            $signature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

            if ($signature !== $signatureKey) {
                Log::warning('Midtrans Invalid Signature: ' . $orderId);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            // 2. Extract Booking Code (Format: BOOKING-YYYYMMDD-ID-RANDOM)
            $parts = explode('-', $orderId);
            if (count($parts) < 3) {
                Log::error('Midtrans Invalid OrderID Format: ' . $orderId);
                return response()->json(['message' => 'Invalid order format'], 400);
            }
            $bookingCode = $parts[0] . '-' . $parts[1] . '-' . $parts[2];

            $booking = Booking::query()->with(['customer', 'car'])->where('booking_code', $bookingCode)->first();
            if (!$booking) {
                Log::error('Midtrans Booking Not Found: ' . $bookingCode);
                return response()->json(['message' => 'Booking not found'], 404);
            }

            $payment = Payment::query()->where('booking_id', $booking->id)->latest()->first();
            if (!$payment) {
                Log::error('Midtrans Payment Record Not Found for Booking: ' . $booking->id);
                return response()->json(['message' => 'Payment record not found'], 404);
            }

            // 3. Process Transaction Status
            DB::transaction(function() use ($booking, $payment, $transactionStatus, $notification) {
                // Log the notification
                PaymentLog::create([
                    'payment_id' => $payment->id,
                    'status' => $transactionStatus,
                    'response' => (array) $notification
                ]);

                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    $this->updateStatus($booking, $payment, 'paid', $notification);
                } else if ($transactionStatus == 'pending') {
                    $this->updateStatus($booking, $payment, 'pending', $notification);
                } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                    $this->updateStatus($booking, $payment, 'failed', $notification);
                }
            });

            return response()->json(['message' => 'Notification processed successfully']);

        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    private function updateStatus(\App\Models\Booking $booking, \App\Models\Payment $payment, string $status, object $notification)
    {
        $customer = $booking->customer;

        if ($status == 'paid') {
            $booking->fill([
                'payment_status' => 'paid',
                'booking_status' => 'confirmed'
            ])->save();
            
            $payment->fill([
                'payment_status' => 'success',
                'payment_date' => now(),
                'transaction_id' => $notification->transaction_id,
                'payment_method' => $notification->payment_type,
                'midtrans_response' => (array) $notification,
                'paid_amount' => $notification->gross_amount
            ])->save();

            $msg = "Halo *{$customer->name}*,\n\nPembayaran Anda untuk Kode Booking *#{$booking->booking_code}* telah berhasil diterima.\n\nStatus Pemesanan: *CONFIRMED*\nTotal Pembayaran: *Rp " . number_format($notification->gross_amount, 0, ',', '.') . "*\n\nTerima kasih telah memilih Siliwangi Rental!";

            // WA Notification (Queued)
            \App\Jobs\SendWhatsAppMessage::dispatch($customer->phone, $msg);
        } else if ($status == 'failed') {
            $booking->fill([
                'payment_status' => 'unpaid',
                'booking_status' => 'cancelled'
            ])->save();

            $payment->fill([
                'payment_status' => 'failed',
                'midtrans_response' => (array) $notification
            ])->save();

            $msg = "Halo *{$customer->name}*,\n\nPembayaran Anda untuk Kode Booking *#{$booking->booking_code}* gagal atau kedaluwarsa.\n\nStatus Pemesanan: *CANCELLED*\nSilakan lakukan pemesanan kembali jika diperlukan.\n\nTerima kasih, Siliwangi Rental.";

            // WA Notification (Queued)
            \App\Jobs\SendWhatsAppMessage::dispatch($customer->phone, $msg);
        }
    }
}
