<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingReport extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string $view = 'filament.pages.booking-report';

    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Laporan Booking';

    protected static ?string $title = 'Laporan Booking & Analisa';

    public string $filter_month;
    public int $filter_year;
    public string $filter_day = 'all';
    public string $filter_driver = 'all';
    public string $filter_area = 'all';
    public string $filter_service = 'all';
    
    public array $daily_data = [];
    public array $monthly_data = [];
    public array $summary = [];

    public static function shouldRegisterNavigation(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasAnyRole(['super-admin', 'owner', 'finance', 'admin', 'operasional']) ?? true;
    }

    public function mount(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        abort_unless($user?->hasAnyRole(['super-admin', 'owner', 'finance', 'admin', 'operasional']) ?? true, 403);
        
        $this->filter_month = (string) now()->month;
        $this->filter_year = (int) now()->year;
        
        $this->generateReport();
    }

    public function updated(string $propertyName)
    {
        // When month changes, reset day filter if it's 'all'
        if ($propertyName === 'filter_month' && $this->filter_month === 'all') {
            $this->filter_day = 'all';
        }
        $this->generateReport();
    }

    public function generateReport()
    {
        if ($this->filter_month === 'all') {
            $this->generateMonthlyData();
        } else {
            $this->generateDailyData();
        }
        $this->generateSummary();
    }

    protected function getFilteredQuery()
    {
        $query = Booking::query();

        if ($this->filter_driver !== 'all') {
            $query->where('with_driver', $this->filter_driver === '1');
        }

        if ($this->filter_service !== 'all') {
            $query->where('rental_type', $this->filter_service);
        }

        if ($this->filter_area !== 'all') {
            $query->where('area', $this->filter_area);
        }

        return $query;
    }

    protected function generateDailyData()
    {
        $startDate = Carbon::createFromDate($this->filter_year, $this->filter_month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth();

        if ($this->filter_day !== 'all') {
            $startDate = Carbon::createFromDate($this->filter_year, $this->filter_month, (int)$this->filter_day)->startOfDay();
            $endDate = $startDate->copy()->endOfDay();
        }

        $query = $this->getFilteredQuery()->whereBetween('created_at', [$startDate, $endDate]);

        // Get daily revenue and count
        $bookings = $query->select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_bookings'),
                DB::raw('SUM(CASE WHEN payment_status = \'paid\' THEN grand_total ELSE 0 END) as revenue')
            ])
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $this->daily_data = [];
        
        if ($this->filter_day !== 'all') {
            // Only 1 day
            $dateStr = $startDate->toDateString();
            $data = $bookings->get($dateStr);
            $this->daily_data[] = [
                'date' => Carbon::parse($dateStr)->format('d M Y'),
                'raw_date' => $dateStr,
                'total_bookings' => $data ? $data->total_bookings : 0,
                'revenue' => $data ? $data->revenue : 0,
            ];
        } else {
            // All days in month
            for ($i = 1; $i <= $startDate->daysInMonth; $i++) {
                $dateStr = $startDate->copy()->day($i)->toDateString();
                $data = $bookings->get($dateStr);
                
                $this->daily_data[] = [
                    'date' => Carbon::parse($dateStr)->format('d M Y'),
                    'raw_date' => $dateStr,
                    'total_bookings' => $data ? $data->total_bookings : 0,
                    'revenue' => $data ? $data->revenue : 0,
                ];
            }
        }
    }

    protected function generateMonthlyData()
    {
        $query = $this->getFilteredQuery()->whereYear('created_at', $this->filter_year);

        $bookings = (clone $query)
            ->select([
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(CASE WHEN payment_status = \'paid\' THEN grand_total ELSE 0 END) as revenue')
            ])
            ->groupBy('month')
            ->pluck('revenue', 'month');

        $bookingCounts = (clone $query)
            ->select([DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as total_bookings')])
            ->groupBy('month')
            ->pluck('total_bookings', 'month');

        $this->monthly_data = [];
        foreach (range(1, 12) as $m) {
            $this->monthly_data[] = [
                'month' => now()->month($m)->format('F'),
                'total_bookings' => $bookingCounts[$m] ?? 0,
                'revenue' => $bookings[$m] ?? 0,
            ];
        }
    }

    protected function generateSummary()
    {
        if ($this->filter_month === 'all') {
            $startDate = Carbon::createFromDate($this->filter_year, 1, 1)->startOfDay();
            $endDate = Carbon::createFromDate($this->filter_year, 12, 31)->endOfDay();
        } else {
            if ($this->filter_day !== 'all') {
                $startDate = Carbon::createFromDate($this->filter_year, (int)$this->filter_month, (int)$this->filter_day)->startOfDay();
                $endDate = $startDate->copy()->endOfDay();
            } else {
                $startDate = Carbon::createFromDate($this->filter_year, (int)$this->filter_month, 1)->startOfDay();
                $endDate = $startDate->copy()->endOfMonth();
            }
        }

        $allBookingsMonth = $this->getFilteredQuery()->whereBetween('created_at', [$startDate, $endDate])->get();

        $totalRevenue = $allBookingsMonth->where('payment_status', 'paid')->sum('grand_total');
        $completedBookings = $allBookingsMonth->where('booking_status', 'completed')->count();
        $cancelledBookings = $allBookingsMonth->where('booking_status', 'cancelled')->count();
        $pendingBookings = $allBookingsMonth->whereIn('booking_status', ['pending', 'confirmed'])->count();
        $totalBookings = $allBookingsMonth->count();
        
        $this->summary = [
            'total_bookings' => $totalBookings,
            'completed_bookings' => $completedBookings,
            'cancelled_bookings' => $cancelledBookings,
            'pending_bookings' => $pendingBookings,
            'total_revenue' => $totalRevenue,
            'success_rate' => $totalBookings > 0 ? round(($completedBookings / $totalBookings) * 100, 1) : 0,
        ];
    }

    public function exportExcel()
    {
        $data = $this->filter_month === 'all' ? $this->monthly_data : $this->daily_data;
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\BookingReportExport($data, $this->filter_month, $this->filter_year),
            "Laporan_Booking_{$this->filter_year}.xlsx"
        );
    }
 
    public function exportPdf()
    {
        $data = $this->filter_month === 'all' ? $this->monthly_data : $this->daily_data;
        $pdf = Pdf::loadView('reports.booking', [
            'report_data' => $data,
            'filter_month' => $this->filter_month,
            'filter_year' => $this->filter_year,
            'summary' => $this->summary,
        ]);
 
        return response()->streamDownload(fn () => print($pdf->output()), "Laporan_Booking_{$this->filter_year}.pdf");
    }
}
