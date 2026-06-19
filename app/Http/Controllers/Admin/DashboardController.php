<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI: Total Booking
        $totalBooking = DB::table('bookings')->count();

        // KPI: Total Revenue (Income from payments)
        $totalRevenue = DB::table('payments')->where('payment_status', 'success')->sum('paid_amount');

        // KPI: Total Expenses
        $totalExpense = DB::table('expenses')->sum('amount');

        // KPI: Mobil Tersedia / Total Mobil
        $availableCars = DB::table('cars')->where('is_available', 1)->count();
        $totalCars = DB::table('cars')->count();

        // KPI: Rental Aktif (on_going)
        $activeRentals = DB::table('bookings')->where('booking_status', 'ongoing')->count();

        // Data for Recent Bookings Table
        $recentBookings = \App\Models\Booking::with(['customer', 'car'])
            ->latest()
            ->take(5)
            ->get();

        // NEW: Active Rentals for Modal
        $activeRentalsList = \App\Models\Booking::with(['car'])
            ->where('booking_status', 'ongoing')
            ->get();

        // Chart Data: Monthly Revenue (Last 6 Months)
        $monthExpr = DB::getDriverName() === 'sqlite'
            ? "CAST(strftime('%m', payment_date) AS INTEGER)"
            : 'MONTH(payment_date)';

        $monthlyRevenue = DB::table('payments')
            ->where('payment_status', 'success')
            ->select([
                DB::raw('SUM(paid_amount) as total'),
                DB::raw("{$monthExpr} as month")
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
                DB::raw("{$expMonthExpr} as month")
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

        // --- PHASE 2: Financial Strategy & CRM ---
        
        // 1. Profit & Loss Analysis (Last 30 Days Breakdown)
        $pnlRevenue = DB::table('payments')
            ->where('payment_status', 'success')
            ->where('payment_date', '>=', now()->subDays(30))
            ->sum('paid_amount');

        $pnlMaintenance = 0;
        $carsWithMaintForPnl = DB::table('cars')
            ->whereNotNull('maintenances')
            ->get(['maintenances']);
            
        $thirtyDaysAgo = now()->subDays(30)->format('Y-m-d');
        foreach ($carsWithMaintForPnl as $car) {
            $maintenances = json_decode($car->maintenances, true);
            if (is_array($maintenances)) {
                foreach ($maintenances as $m) {
                    $mDate = $m['maintenance_date'] ?? null;
                    if ($mDate && $mDate >= $thirtyDaysAgo) {
                        $pnlMaintenance += (float) ($m['cost'] ?? 0);
                    }
                }
            }
        }

        // Driver Fee Expense Calculation (Daily Fee * Days)
        $pnlDriverFees = DB::table('bookings')
            ->join('drivers', 'bookings.driver_id', '=', 'drivers.id')
            ->where('bookings.booking_status', '!=', 'cancelled')
            ->where('bookings.created_at', '>=', now()->subDays(30))
            ->select(DB::raw('SUM(bookings.total_day * drivers.daily_fee) as total'))
            ->value('total') ?? 0;

        // Fuel Expense (from expenses table with category 'fuel')
        $pnlFuel = DB::table('expenses')
            ->where(function($query) {
                $query->where('category', 'fuel')
                      ->orWhere('category', 'like', '%Bahan Bakar%')
                      ->orWhere('category', 'like', '%Bensin%');
            })
            ->where('date', '>=', now()->subDays(30))
            ->sum('amount');

        $netProfit = $pnlRevenue - $pnlMaintenance - $pnlDriverFees - $pnlFuel;

        // 2. Midtrans Monitor (Real-time stats)
        $paymentStats = [
            'success' => DB::table('payments')->where('payment_status', 'success')->count(),
            'pending' => DB::table('payments')->where('payment_status', 'pending')->count(),
            'failed' => DB::table('payments')->whereIn('payment_status', ['failed', 'expire', 'cancel'])->count(),
        ];
        $totalPayments = array_sum($paymentStats);
        $successRate = $totalPayments > 0 
            ? round(($paymentStats['success'] / $totalPayments) * 100, 1) 
            : 0;

        // 3. Deposit Ledger
        // Security deposits held are typically fixed or based on car type, here we use dp_amount for ongoing as a proxy
        $totalDepositsHeld = DB::table('bookings')->where('booking_status', 'ongoing')->sum('dp_amount');
        $pendingRefunds = DB::table('bookings')
            ->where('booking_status', 'completed')
            ->where('remaining_payment', '<', 0)
            ->sum(DB::raw('ABS(remaining_payment)'));

        // 4. Customer Blacklist (Retrieve inactive customers)
        $blacklistedCustomers = DB::table('customers')->where('is_active', false)->get();

        // --- PHASE 3: Business Intelligence & Predictions ---
        
        // 1. Peak Season Predictor (Historical Trend)
        $peakSeasonLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $peakSeasonData = [12, 19, 15, 25, 32, 45, 38, 28, 22, 18, 25, 50]; // Mocked based on "May=32" trend
        
        // 2. Branch Performance
        $branchPerformance = DB::table('bookings')
            ->join('stores', 'bookings.store_id', '=', 'stores.id')
            ->select([
                'stores.name',
                DB::raw('COUNT(*) as total')
            ])
            ->groupBy('stores.name')
            ->get();

        // --- PHASE 1: Operational & Car Intelligence ---
        // 1. GPS Car (Mocked data for premium demo)
        $gpsCar = [
            [
                'name' => 'Toyota Avanza - B 1234 ABC', 
                'lat' => -6.9175, 'lng' => 107.6191, 
                'status' => 'Moving', 'speed' => '45 km/h',
                'last_update' => now()->diffForHumans()
            ],
            [
                'name' => 'Honda Brio - D 5678 EFG', 
                'lat' => -6.9147, 'lng' => 107.6098, 
                'status' => 'Idle', 'speed' => '0 km/h',
                'last_update' => now()->subMinutes(5)->diffForHumans()
            ],
            [
                'name' => 'Mitsubishi Xpander - Z 9012 HIJ', 
                'lat' => -6.9201, 'lng' => 107.6325, 
                'status' => 'Moving', 'speed' => '62 km/h',
                'last_update' => now()->diffForHumans()
            ],
            [
                'name' => 'Toyota Innova - F 4321 KLY', 
                'lat' => -6.9250, 'lng' => 107.6150, 
                'status' => 'Parking', 'speed' => '0 km/h',
                'last_update' => now()->subHours(2)->diffForHumans()
            ],
        ];

        // 2. Expiring Documents (Refactored for Blade consistency)
        $cars = DB::table('cars')
            ->select([
                'car_name',
                'plate_number',
                'stnk_expiry',
                'tax_expiry'
            ])
            ->where('stnk_expiry', '<=', now()->addDays(30))
            ->orWhere('tax_expiry', '<=', now()->addDays(30))
            ->get();

        $expiringDocs = collect();
        foreach ($cars as $car) {
            if ($car->stnk_expiry && \Carbon\Carbon::parse($car->stnk_expiry)->diffInDays(now()) <= 30) {
                $expiringDocs->push((object)[
                    'doc' => 'STNK',
                    'days' => \Carbon\Carbon::parse($car->stnk_expiry)->diffInDays(now()),
                    'car' => $car->car_name,
                    'plate' => $car->plate_number
                ]);
            }
            if ($car->tax_expiry && \Carbon\Carbon::parse($car->tax_expiry)->diffInDays(now()) <= 30) {
                $expiringDocs->push((object)[
                    'doc' => 'TAX',
                    'days' => \Carbon\Carbon::parse($car->tax_expiry)->diffInDays(now()),
                    'car' => $car->car_name,
                    'plate' => $car->plate_number
                ]);
            }
        }

        // 3. Upcoming Maintenance (Refactored to fetch from cars.maintenances JSON)
        $upcomingMaintenance = collect();
        $carsWithMaint = DB::table('cars')
            ->whereNotNull('maintenances')
            ->get(['car_name', 'plate_number', 'maintenances']);

        foreach ($carsWithMaint as $car) {
            $maintenances = json_decode($car->maintenances, true);
            if (is_array($maintenances)) {
                foreach ($maintenances as $m) {
                    $mDate = $m['maintenance_date'] ?? null;
                    if ($mDate && $mDate >= now()->format('Y-m-d') && $mDate <= now()->addDays(30)->format('Y-m-d')) {
                        $upcomingMaintenance->push((object)[
                            'car_name' => $car->car_name,
                            'plate_number' => $car->plate_number,
                            'date' => $mDate,
                            'type' => $m['description'] ?? 'Servis Berkala',
                            'amount' => (float) ($m['cost'] ?? 0)
                        ]);
                    }
                }
            }
        }
        $upcomingMaintenance = $upcomingMaintenance->sortBy('date')->take(5);

        return view('dashboard', compact(
            'totalBooking', 'totalRevenue', 'totalExpense', 'availableCars', 'totalCars', 
            'activeRentals', 'recentBookings', 'months', 'revenueData', 'expenseData',
            'netProfit', 'successRate', 'paymentStats', 'totalDepositsHeld', 
            'pendingRefunds', 'blacklistedCustomers', 'gpsCar', 'expiringDocs', 
            'upcomingMaintenance', 'peakSeasonLabels', 'peakSeasonData', 'branchPerformance',
            'pnlRevenue', 'pnlMaintenance', 'pnlDriverFees', 'pnlFuel'
        ));
    }
}
