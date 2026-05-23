<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckLateBookings extends Command
{
    protected $signature = 'bookings:check-late';
    protected $description = 'Cek booking yang telat mengembalikan mobil dan kirim notifikasi';

    public function handle()
    {
        $this->info('Memulai pengecekan booking telat...');

        $now = Carbon::now();
        
        // Cari booking yang statusnya 'ongoing' tapi sudah lewat return_date
        $lateBookings = Booking::where('booking_status', 'ongoing')
            ->where('return_date', '<', $now)
            ->get();

        if ($lateBookings->isEmpty()) {
            $this->info('Tidak ada booking yang telat.');
            return;
        }

        foreach ($lateBookings as $booking) {
            $diffHours = $now->diffInHours($booking->return_date);
            
            // Hitung denda sementara (late_fee dari mobil * jumlah jam telat)
            $penaltyPerHour = $booking->car->late_fee ?? 50000; // default 50rb jika tidak diatur
            $currentLateFee = $diffHours * $penaltyPerHour;

            // Update data booking
            $booking->update([
                'late_fee' => $currentLateFee,
                // Kita tidak update grand_total di sini agar tidak membingungkan 
                // hingga admin melakukan "Complete"
            ]);

            // Kirim Notifikasi WhatsApp
            $this->sendWhatsAppAlert($booking, $diffHours, $currentLateFee);

            $this->warn("Booking {$booking->booking_code} telat {$diffHours} jam. Denda: Rp " . number_format($currentLateFee));
        }

        $this->info('Pengecekan selesai.');
    }

    protected function sendWhatsAppAlert($booking, $hours, $fee)
    {
        $phone = $booking->customer->phone;
        $customerName = $booking->customer->name;
        $carName = $booking->car->car_name;
        $returnTime = $booking->return_date->format('d/m/Y H:i');

        $message = "🔔 *PEMBERITAHUAN KETERLAMBATAN - SILIWANGI RENTAL*\n\n" .
            "Halo Kak *{$customerName}*,\n" .
            "Kami menginformasikan bahwa waktu sewa untuk unit *{$carName}* telah melewati batas waktu pengembalian pada *{$returnTime}*.\n\n" .
            "⏱️ *Keterlambatan:* {$hours} Jam\n" .
            "💰 *Estimasi Denda:* Rp " . number_format($fee, 0, ',', '.') . "\n\n" .
            "Mohon segera mengembalikan unit ke cabang terdekat atau hubungi admin jika ingin melakukan perpanjangan sewa.\n\n" .
            "Terima kasih.";

        // Dispatch Job (Queued)
        \App\Jobs\SendWhatsAppMessage::dispatch($phone, $message);
    }
}
