<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoExpireBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-expire-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredBookings = \App\Models\Booking::where('booking_status', 'pending')
            ->where('expired_at', '<=', now())
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('No pending bookings found to expire.');
            return;
        }

        foreach ($expiredBookings as $booking) {
            $booking->update([
                'booking_status' => 'expired'
            ]);
            
            $this->info("Booking #{$booking->booking_code} has been expired.");
        }

        $this->info('Auto-expire process completed.');
    }
}
