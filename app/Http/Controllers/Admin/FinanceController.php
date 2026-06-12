<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class FinanceController extends Controller
{
    public function index()
    {
        $totalIncome = Payment::query()->where('payment_status', 'success')->sum('paid_amount');
        $totalExpense = Expense::query()->sum('amount');
        $netIncome = $totalIncome - $totalExpense;

        $recentPayments = Payment::query()->with('booking.customer')->latest()->take(10)->get();
        $recentExpenses = Expense::query()->latest()->take(10)->get();

        // Chart Data: Monthly Revenue (Last 6 Months)
        $monthExpr = DB::getDriverName() === 'sqlite'
            ? "CAST(strftime('%m', payment_date) AS INTEGER)"
            : 'MONTH(payment_date)';

        $monthlyRevenue = DB::table('payments')
            ->where('payment_status', 'success')
            ->select([
                DB::raw('SUM(paid_amount) as total'),
                DB::raw("{$monthExpr} as month"),
            ])
            ->where('payment_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->all();

        // Chart Data: Monthly Expenses (Last 6 Months)
        $expMonthExpr = DB::getDriverName() === 'sqlite'
            ? "CAST(strftime('%m', date) AS INTEGER)"
            : 'MONTH(date)';

        $monthlyExpenses = DB::table('expenses')
            ->select([
                DB::raw('SUM(amount) as total'),
                DB::raw("{$expMonthExpr} as month"),
            ])
            ->where('date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->all();

        // Prepare labels and data for Chart.js
        $months = [];
        $revenueData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i)->month;
            $months[] = now()->subMonths($i)->format('M');
            $revenueData[] = $monthlyRevenue[$m] ?? 0;
            $expenseData[] = $monthlyExpenses[$m] ?? 0;
        }

        // Payment status distribution (Midtrans payment status stats)
        $paymentStats = [
            'success' => Payment::query()->where('payment_status', 'success')->count(),
            'pending' => Payment::query()->where('payment_status', 'pending')->count(),
            'failed' => Payment::query()->whereIn('payment_status', ['failed', 'expire', 'cancel'])->count(),
        ];

        $paymentStats['labels'] = ['Success', 'Pending', 'Failed'];
        $paymentStats['data'] = [$paymentStats['success'], $paymentStats['pending'], $paymentStats['failed']];

        // Check if user wants premium or legacy dashboard
        if (View::exists('admin.finance.index')) {
            return view('admin.finance.index', compact('totalIncome', 'totalExpense', 'netIncome', 'recentPayments', 'recentExpenses', 'months', 'revenueData', 'expenseData', 'paymentStats'));
        }

        return view('dashboard', compact('totalIncome', 'totalExpense', 'netIncome', 'recentPayments', 'recentExpenses', 'months', 'revenueData', 'expenseData', 'paymentStats'));
    }

    public function payments(Request $request)
    {
        $query = Payment::query()->with(['booking.customer', 'booking.car']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('payment_code', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('booking', function ($qb) use ($search) {
                        $qb->where('booking_code', 'like', "%{$search}%")
                            ->orWhereHas('customer', function ($qc) use ($search) {
                                $qc->where('name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('payment_date', $request->input('date'));
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->input('method'));
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        // Calculate statistics for the premium dashboard
        $stats = [
            'total_income_month' => Payment::query()->where('payment_status', 'success')
                ->whereMonth('payment_date', Carbon::now()->month)
                ->sum('paid_amount'),
            'pending_payment_count' => Payment::query()->where('payment_status', 'pending')->count(),
            'pending_payment_amount' => Payment::query()->where('payment_status', 'pending')->sum('gross_amount'),
            'refund_count' => Payment::query()->where('payment_status', 'refund')->count(),
            'refund_amount' => Payment::query()->where('payment_status', 'refund')->sum('paid_amount'),
            'midtrans_balance' => Payment::query()->where('payment_status', 'success')->sum('paid_amount'),
        ];

        // Chart Data: Monthly Revenue (Last 6 Months)
        $monthExpr = DB::getDriverName() === 'sqlite'
            ? "CAST(strftime('%m', payment_date) AS INTEGER)"
            : 'MONTH(payment_date)';

        $monthlyRevenue = DB::table('payments')
            ->where('payment_status', 'success')
            ->select([
                DB::raw('SUM(paid_amount) as total'),
                DB::raw("{$monthExpr} as month"),
            ])
            ->where('payment_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->all();

        // Chart Data: Monthly Expenses (Last 6 Months)
        $expMonthExpr = DB::getDriverName() === 'sqlite'
            ? "CAST(strftime('%m', date) AS INTEGER)"
            : 'MONTH(date)';

        $monthlyExpenses = DB::table('expenses')
            ->select([
                DB::raw('SUM(amount) as total'),
                DB::raw("{$expMonthExpr} as month"),
            ])
            ->where('date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->all();

        // Prepare labels and data for Chart.js
        $months = [];
        $revenueData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i)->month;
            $months[] = now()->subMonths($i)->format('M');
            $revenueData[] = $monthlyRevenue[$m] ?? 0;
            $expenseData[] = $monthlyExpenses[$m] ?? 0;
        }

        $paymentStats = [
            'success' => Payment::query()->where('payment_status', 'success')->count(),
            'pending' => Payment::query()->where('payment_status', 'pending')->count(),
            'failed' => Payment::query()->whereIn('payment_status', ['failed', 'expire', 'cancel'])->count(),
        ];

        $paymentStats['labels'] = ['Success', 'Pending', 'Failed'];
        $paymentStats['data'] = [$paymentStats['success'], $paymentStats['pending'], $paymentStats['failed']];

        return view('admin.payments.index', compact('payments', 'stats', 'months', 'revenueData', 'expenseData', 'paymentStats'));
    }

    public function showPayment(Payment $payment)
    {
        $payment->load(['booking.customer', 'booking.car']);

        return response()->json($payment);
    }

    public function syncMidtrans()
    {
        try {
            Artisan::call('payments:sync-midtrans');

            return response()->json([
                'success' => true,
                'message' => 'Sinkronisasi status pembayaran dengan Midtrans API berhasil diselesaikan!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal sinkronisasi: '.$e->getMessage(),
            ], 500);
        }
    }

    public function export(Request $request)
    {
        $query = Payment::query()->with(['booking.customer', 'booking.car']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('payment_code', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('booking', function ($qb) use ($search) {
                        $qb->where('booking_code', 'like', "%{$search}%")
                            ->orWhereHas('customer', function ($qc) use ($search) {
                                $qc->where('name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('payment_date', $request->input('date'));
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->input('method'));
        }

        $payments = $query->latest()->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=laporan-pembayaran-'.date('Y-m-d').'.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID Pembayaran', 'Invoice', 'Kode Booking', 'Pelanggan', 'Metode Pembayaran', 'Nominal', 'Status', 'Tanggal Pembayaran']);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->payment_code,
                    $payment->booking->booking_code ?? '-',
                    $payment->booking->customer->name ?? '-',
                    strtoupper($payment->payment_method ?? 'Midtrans'),
                    $payment->paid_amount,
                    strtoupper($payment->payment_status),
                    $payment->payment_date ? $payment->payment_date->toDateTimeString() : '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
