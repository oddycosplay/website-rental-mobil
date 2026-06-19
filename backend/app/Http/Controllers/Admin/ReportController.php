<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $stats = [
            'total_revenue' => (float) Payment::query()->where('payment_status', '=', 'success', 'and')
                ->where('payment_date', '>=', $startDate->startOfDay(), 'and')
                ->where('payment_date', '<=', $endDate->endOfDay(), 'and')
                ->selectRaw('SUM(paid_amount) as total_sum')
                ->value('total_sum'),
            'total_bookings' => (int) Booking::query()
                ->where('created_at', '>=', $startDate->startOfDay(), 'and')
                ->where('created_at', '<=', $endDate->endOfDay(), 'and')
                ->value(DB::raw('count(*)')),
            'avg_duration' => (float) Booking::query()
                ->where('created_at', '>=', $startDate->startOfDay(), 'and')
                ->where('created_at', '<=', $endDate->endOfDay(), 'and')
                ->selectRaw('AVG(total_day) as avg_day')
                ->value('avg_day') ?? 0,
            'total_refunds' => (int) Payment::query()->where('payment_status', '=', 'refund', 'and')
                ->where('payment_date', '>=', $startDate->startOfDay(), 'and')
                ->where('payment_date', '<=', $endDate->endOfDay(), 'and')
                ->value(DB::raw('count(*)')),
        ];

        // Monthly Revenue for Chart
        $monthlyRevenue = Payment::query()->where('payment_status', '=', 'success', 'and')
            ->select([
                DB::raw('SUM(paid_amount) as total'),
                DB::raw('DATE(payment_date) as date')
            ])
            ->where('payment_date', '>=', now()->subDays(30), 'and')
            ->where('payment_date', '<=', now(), 'and')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Car type distribution
        $carTypeDistribution = Booking::query()->join('cars', 'bookings.car_id', '=', 'cars.id', 'inner', false)
            ->select(['cars.type_name as name', DB::raw('count(*) as total')])
            ->groupBy('cars.type_name')
            ->get();

        // Segment Revenue (Pribadi vs Perusahaan)
        $segmentRevenue = Payment::query()->where('payments.payment_status', '=', 'success', 'and')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id', 'inner', false)
            ->join('cars', 'bookings.car_id', '=', 'cars.id', 'inner', false)
            ->select(['cars.category', DB::raw('SUM(payments.paid_amount) as total')])
            ->groupBy('cars.category')
            ->get();

        return view('admin.reports.index', compact('stats', 'monthlyRevenue', 'carTypeDistribution', 'segmentRevenue', 'startDate', 'endDate'));
    }
    public function bookings(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $bookings = Booking::with(['user', 'car', 'branch'])
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->when($request->status, function($query) use ($request) {
                return $query->where('booking_status', $request->status);
            })
            ->latest()
            ->get();

        return view('admin.reports.bookings', compact('bookings', 'startDate', 'endDate'));
    }

    public function revenue(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $incomes = (float) Booking::query()->where('payment_status', '=', 'paid', 'and')
            ->where('created_at', '>=', $startDate->startOfDay(), 'and')
            ->where('created_at', '<=', $endDate->endOfDay(), 'and')
            ->selectRaw('SUM(grand_total) as total_income')
            ->value('total_income');

        $expenses = (float) Expense::query()
            ->where('date', '>=', $startDate->toDateString(), 'and')
            ->where('date', '<=', $endDate->toDateString(), 'and')
            ->selectRaw('SUM(amount) as total_expense')
            ->value('total_expense');

        $payments = Payment::query()->with('booking')
            ->where('payment_date', '>=', $startDate->startOfDay(), 'and')
            ->where('payment_date', '<=', $endDate->endOfDay(), 'and')
            ->where('payment_status', '=', 'success', 'and')
            ->get();

        return view('admin.reports.revenue', compact('incomes', 'expenses', 'payments', 'startDate', 'endDate'));
    }

    public function analytics()
    {
        // 1. Car Utilization Rate (Last 30 days)
        // (Total days rented / Total available days for each car)
        $cars = \App\Models\Car::query()->get();
        $utilizationData = $cars->map(function($car) {
            $rentedDays = (float) Booking::query()->where('car_id', '=', $car->id, 'and')
                ->where('booking_status', '=', 'completed', 'and')
                ->where('pickup_date', '>=', now()->subDays(30), 'and')
                ->selectRaw('SUM(total_day) as sum_day')
                ->value('sum_day') ?? 0;
            
            $rate = ($rentedDays / 30) * 100;
            return [
                'name' => $car->car_name,
                'rate' => round(min($rate, 100), 1),
            ];
        })->sortByDesc('rate')->take(5);

        // 2. Customer Lifetime Value (Top 5)
        $topCustomers = \App\Models\User::query()->whereHas('roles', function($q) {
                $q->where('name', '=', 'customer', 'and');
            })
            ->withSum(['bookings' => function($query) {
                $query->where('payment_status', '=', 'paid', 'and');
            }], 'grand_total')
            ->orderBy('bookings_sum_grand_total', 'desc')
            ->take(5)
            ->get();

        // 3. Profit vs Loss (Last 6 Months)
        $profitData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $income = (float) Payment::query()->where('payment_status', '=', 'success', 'and')
                ->whereMonth('payment_date', '=', $month->month, 'and')
                ->whereYear('payment_date', '=', $month->year, 'and')
                ->selectRaw('SUM(paid_amount) as sum_paid')
                ->value('sum_paid');
            
            $expense = (float) Expense::query()
                ->whereMonth('date', '=', $month->month, 'and')
                ->whereYear('date', '=', $month->year, 'and')
                ->selectRaw('SUM(amount) as sum_amount')
                ->value('sum_amount');
            
            $profitData[] = [
                'month' => $month->format('M Y'),
                'income' => $income,
                'expense' => $expense,
                'profit' => $income - $expense
            ];
        }

        return view('admin.reports.analytics', compact('utilizationData', 'topCustomers', 'profitData'));
    }
}
