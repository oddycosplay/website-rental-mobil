<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Models\Booking;
use App\Models\PaymentLog;
use App\Services\MidtransService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncMidtransPayments extends Command
{
    protected $signature = 'payments:sync-midtrans';
    protected $description = 'Sinkronisasi status pembayaran dengan Midtrans API';

    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        parent::__construct();
        $this->midtrans = $midtrans;
    }

    public function handle()
    {
        $this->info('Memulai sinkronisasi pembayaran...');

        // Ambil pembayaran yang masih pending
        $pendingPayments = Payment::where('payment_status', 'pending')->get();

        if ($pendingPayments->isEmpty()) {
            $this->info('Tidak ada pembayaran pending untuk disinkronkan.');
            return;
        }

        foreach ($pendingPayments as $payment) {
            try {
                // Midtrans response biasanya berisi transaction_id atau order_id di kolom midtrans_response
                // Jika tidak ada, kita coba cari dari booking_code
                $orderId = $payment->transaction_id ?? $payment->booking->booking_code;
                
                // Cari status terbaru dari Midtrans
                $statusResponse = $this->midtrans->checkStatus($orderId);
                $transactionStatus = $statusResponse->transaction_status;

                DB::transaction(function() use ($payment, $transactionStatus, $statusResponse) {
                    // Simpan log
                    PaymentLog::create([
                        'payment_id' => $payment->id,
                        'status' => $transactionStatus,
                        'response' => (array) $statusResponse,
                    ]);

                    if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                        $this->updateStatus($payment, 'paid', $statusResponse);
                    } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                        $this->updateStatus($payment, 'failed', $statusResponse);
                    }
                });

                $this->info("Payment #{$payment->id} synchronized: {$transactionStatus}");

            } catch (\Exception $e) {
                $this->error("Gagal sinkronisasi Payment #{$payment->id}: " . $e->getMessage());
                Log::error('SyncMidtransPayments Error: ' . $e->getMessage());
            }
        }

        $this->info('Sinkronisasi selesai.');
    }

    private function updateStatus($payment, $status, $response)
    {
        $booking = $payment->booking;

        if ($status == 'paid') {
            $booking->update([
                'payment_status' => 'paid',
                'booking_status' => 'confirmed'
            ]);
            
            $payment->update([
                'payment_status' => 'success',
                'payment_date' => now(),
                'transaction_id' => $response->transaction_id,
                'payment_method' => $response->payment_type,
                'midtrans_response' => (array) $response,
                'paid_amount' => $response->gross_amount
            ]);

            // Kirim WA Notifikasi (Opsional, sudah ada di callback tapi jaga-jaga jika callback gagal)
            $message = "✅ *PEMBAYARAN BERHASIL - SILIWANGI RENTAL*\n\nHalo Kak,\nPembayaran untuk pesanan *{$booking->booking_code}* telah kami terima. Pesanan Anda kini telah dikonfirmasi.";
            \App\Jobs\SendWhatsAppMessage::dispatch($booking->customer->phone, $message);

        } else if ($status == 'failed') {
            $booking->update([
                'payment_status' => 'unpaid',
                'booking_status' => 'cancelled'
            ]);

            $payment->update([
                'payment_status' => 'failed',
                'midtrans_response' => (array) $response
            ]);
        }
    }
}
