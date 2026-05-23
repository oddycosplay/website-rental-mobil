<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceController extends Controller
{
    public function index()
    {
        $totalIncome = Booking::where('payment_status', 'paid')->sum('grand_total');
        $totalExpense = Expense::sum('amount');
        $netIncome = $totalIncome - $totalExpense;

        $recentPayments = Payment::with('booking.customer')->latest()->take(10)->get();
        $recentExpenses = Expense::latest()->take(10)->get();

        // Check if user wants premium or legacy dashboard
        if (\Illuminate\Support\Facades\View::exists('admin.finance.index')) {
            return view('admin.finance.index', compact('totalIncome', 'totalExpense', 'netIncome', 'recentPayments', 'recentExpenses'));
        }
        
        return view('dashboard', compact('totalIncome', 'totalExpense', 'netIncome', 'recentPayments', 'recentExpenses'));
    }

    public function payments()
    {
        $payments = Payment::with(['booking.customer', 'booking.car'])
            ->latest()
            ->paginate(15);

        // Calculate statistics for the premium dashboard
        $stats = [
            'total_income_month' => Payment::where('payment_status', 'success')
                ->whereMonth('payment_date', Carbon::now()->month)
                ->sum('paid_amount'),
            'pending_payment_count' => Payment::where('payment_status', 'pending')->count(),
            'pending_payment_amount' => Payment::where('payment_status', 'pending')->sum('gross_amount'),
            'refund_count' => Payment::where('payment_status', 'refund')->count(),
            'refund_amount' => Payment::where('payment_status', 'refund')->sum('paid_amount'),
            'midtrans_balance' => Payment::where('payment_status', 'success')->sum('paid_amount'), // Placeholder
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function showPayment(Payment $payment)
    {
        $payment->load(['booking.customer', 'booking.car']);
        return response()->json($payment);
    }
}
