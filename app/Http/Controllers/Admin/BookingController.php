<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['customer', 'car', 'branch', 'driver'])
            ->latest()
            ->paginate(10);

        // Statistics
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('booking_status', 'pending')->count(),
            'ongoing' => Booking::where('booking_status', 'ongoing')->count(),
            'completed' => Booking::where('booking_status', 'completed')->count(),
            'revenue' => Booking::where('payment_status', 'paid')->sum('grand_total'),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['customer', 'car', 'branch', 'driver', 'promo']);
        return response()->json($booking);
    }

    public function invoice($code)
    {
        $booking = Booking::where('booking_code', $code)
            ->with(['customer', 'car', 'branch', 'driver', 'promo'])
            ->firstOrFail();

        // Check if dompdf is installed
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', compact('booking'));
            return $pdf->download('Invoice-' . $booking->booking_code . '.pdf');
        }

        // Fallback to HTML view if dompdf is not installed
        return view('pdf.invoice', compact('booking'));
    }
}
