<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::query()->with(['customer', 'car', 'branch', 'driver']);

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($qc) use ($search) {
                        $qc->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('car', function ($qa) use ($search) {
                        $qa->where('car_name', 'like', "%{$search}%")
                            ->orWhere('plate_number', 'like', "%{$search}%");
                    });
            });
        }

        // Advanced Filters
        if ($request->filled('booking_status')) {
            $query->where('booking_status', '=', $request->input('booking_status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', '=', $request->input('payment_status'));
        }

        if ($request->filled('with_driver')) {
            $query->where('with_driver', '=', $request->input('with_driver'));
        }

        if ($request->filled('store_id')) {
            $query->where('store_id', '=', $request->input('store_id'));
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();

        // Statistics
        $stats = [
            'total' => Booking::query()->count(),
            'pending' => Booking::query()->where('booking_status', '=', 'pending')->count(),
            'ongoing' => Booking::query()->where('booking_status', '=', 'ongoing')->count(),
            'completed' => Booking::query()->where('booking_status', '=', 'completed')->count(),
            'revenue' => Booking::query()->where('payment_status', '=', 'paid')->sum('grand_total'),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['customer', 'car', 'branch', 'driver', 'promo']);
        return response()->json($booking);
    }

    public function invoice(Request $request, string $code)
    {
        $booking = Booking::query()->where('booking_code', '=', $code)
            ->with(['customer', 'car', 'branch', 'driver', 'promo', 'payment'])
            ->firstOrFail();

        // Dynamically create payment / snap token if missing
        if (in_array($booking->payment_status, ['unpaid', 'pending'])) {
            if (!$booking->payment || !$booking->payment->snap_token) {
                try {
                    $midtrans = app(\App\Services\MidtransService::class);
                    $snapToken = $midtrans->getSnapToken($booking);

                    if (!$booking->payment) {
                        $payment = \App\Models\Payment::create([
                            'booking_id'     => $booking->id,
                            'payment_code'   => 'PAY-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5)),
                            'snap_token'     => $snapToken,
                            'gross_amount'   => $booking->grand_total,
                            'payment_status' => 'pending',
                        ]);
                        $booking->setRelation('payment', $payment);
                    } else {
                        $booking->payment->update([
                            'snap_token' => $snapToken,
                            'gross_amount' => $booking->grand_total,
                        ]);
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Dynamic Midtrans Token Generation Error: ' . $e->getMessage());
                }
            }
        }

        $viewData = ['booking' => $booking];

        // Check if the request explicitly wants a PDF download
        if ($request->has('download')) {
            // Render Blade to HTML string first, then load into DomPDF
            // This avoids Pdf::loadView() whose stub incorrectly types $data as object
            $html = view('pdf.invoice', $viewData)->render();
            /** @var \Barryvdh\DomPDF\PDF $pdf */
            $pdf = Pdf::loadHTML($html);
            return $pdf->download('Invoice-' . $booking->booking_code . '.pdf');
        }

        // Show HTML view by default to allow Midtrans interaction
        return view('pdf.invoice', $viewData);
    }

    public function approve(Booking $booking)
    {
        $booking->update([
            'booking_status' => 'confirmed'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking #' . $booking->booking_code . ' berhasil disetujui!'
        ]);
    }

    public function cancel(Booking $booking)
    {
        $booking->update([
            'booking_status' => 'cancelled'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking #' . $booking->booking_code . ' berhasil dibatalkan!'
        ]);
    }
}
