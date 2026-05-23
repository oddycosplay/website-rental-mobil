<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;

class AnalyticsOverview extends Widget
{
    protected static ?int $sort = -1; // Top of dashboard
    protected int | string | array $columnSpan = 'full';
    protected static string $view = 'filament.widgets.analytics-overview';

    public string $period = 'this_month';
    public string $serviceType = 'all';

    protected function getListeners(): array
    {
        return ['refreshAnalytics' => '$refresh'];
    }

    public function updatedPeriod(): void
    {
        $this->dispatch('analyticsUpdated');
    }

    public function updatedServiceType(): void
    {
        $this->dispatch('analyticsUpdated');
    }

    protected function getDateRange(): array
    {
        return match ($this->period) {
            'today'        => [now()->startOfDay(), now()->endOfDay()],
            'this_week'    => [now()->startOfWeek(), now()->endOfWeek()],
            'last_month'   => [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()],
            'this_year'    => [now()->startOfYear(), now()->endOfYear()],
            'last_7_days'  => [now()->subDays(6)->startOfDay(), now()->endOfDay()],
            'last_30_days' => [now()->subDays(29)->startOfDay(), now()->endOfDay()],
            default        => [now()->startOfMonth(), now()->endOfMonth()], // this_month
        };
    }

    protected function getPeriodLabel(): string
    {
        return match ($this->period) {
            'today'        => 'Hari Ini (' . now()->translatedFormat('d M Y') . ')',
            'this_week'    => 'Minggu Ini',
            'last_month'   => 'Bulan Lalu (' . now()->subMonth()->translatedFormat('M Y') . ')',
            'this_year'    => 'Tahun Ini (' . now()->year . ')',
            'last_7_days'  => '7 Hari Terakhir',
            'last_30_days' => '30 Hari Terakhir',
            default        => 'Bulan Ini (' . now()->translatedFormat('M Y') . ')',
        };
    }

    protected function baseQuery()
    {
        [$start, $end] = $this->getDateRange();

        $query = Booking::query()
            ->when(Filament::getTenant(), fn($q, $tenant) => $q->where('bookings.store_id', $tenant->id))
            ->whereBetween('created_at', [$start, $end]);

        if ($this->serviceType === 'with_driver') {
            $query->where('with_driver', true);
        } elseif ($this->serviceType === 'without_driver') {
            $query->where('with_driver', false);
        }

        return $query;
    }

    #[Computed]
    public function stats(): array
    {
        $totalRevenue = (clone $this->baseQuery())
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        $totalBookings = (clone $this->baseQuery())
            ->whereIn('booking_status', ['confirmed', 'ongoing', 'completed'])
            ->count();

        $avgDuration = (clone $this->baseQuery())
            ->where('booking_status', 'completed')
            ->avg('total_day') ?? 0;

        $refundedCancelled = (clone $this->baseQuery())
            ->whereIn('booking_status', ['cancelled'])
            ->orWhere('payment_status', 'refunded')
            ->when(Filament::getTenant(), fn($q, $tenant) => $q->where('bookings.store_id', $tenant->id))
            ->count();

        return [
            'total_revenue'    => $totalRevenue,
            'total_bookings'   => $totalBookings,
            'avg_duration'     => round($avgDuration, 1),
            'refunded'         => $refundedCancelled,
        ];
    }

    #[Computed]
    public function dailyRevenueChart(): array
    {
        [$start, $end] = $this->getDateRange();

        // Determine granularity: use hours for 1 day, days otherwise
        $days = $start->diffInDays($end);

        $query = (clone $this->baseQuery())
            ->where('payment_status', 'paid')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(grand_total) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Build a full date range with 0-fill
        $labels = [];
        $data = [];
        $current = $start->copy()->startOfDay();

        while ($current->lte($end)) {
            $key = $current->format('Y-m-d');
            $label = $days <= 1 ? $current->format('d M') : ($days <= 31 ? $current->format('d M') : $current->format('M Y'));
            $labels[] = $label;
            $data[] = (float) ($query->get($key)->total ?? 0);
            $current->addDay();
        }

        return [
            'labels' => $labels,
            'data'   => $data,
        ];
    }

    #[Computed]
    public function vehicleTypeChart(): array
    {
        [$start, $end] = $this->getDateRange();

        $serviceFilter = $this->serviceType;
        $tenant = Filament::getTenant();

        $data = Booking::query()
            ->when($tenant, fn($q, $t) => $q->where('bookings.store_id', $t->id))
            ->whereBetween('bookings.created_at', [$start, $end])
            ->when($serviceFilter === 'with_driver', fn($q) => $q->where('with_driver', true))
            ->when($serviceFilter === 'without_driver', fn($q) => $q->where('with_driver', false))
            ->join('cars', 'bookings.car_id', '=', 'cars.id')
            ->select('cars.type_name as name', DB::raw('count(*) as total'))
            ->groupBy('cars.type_name')
            ->orderByDesc('total')
            ->get();

        $palette = ['#D4A017', '#0A2342', '#2EC4B6', '#E84855', '#F26419', '#6B4226', '#8B5CF6'];

        return [
            'labels'          => $data->pluck('name')->toArray(),
            'data'            => $data->pluck('total')->toArray(),
            'backgroundColors' => collect($data)->keys()->map(fn($i) => $palette[$i % count($palette)])->toArray(),
        ];
    }

    public function getPeriodOptions(): array
    {
        return [
            'today'        => 'Hari Ini (' . now()->translatedFormat('d M Y') . ')',
            'this_week'    => 'Minggu Ini',
            'this_month'   => 'Bulan Ini (' . now()->translatedFormat('M Y') . ')',
            'last_month'   => 'Bulan Lalu (' . now()->subMonth()->translatedFormat('M Y') . ')',
            'last_7_days'  => '7 Hari Terakhir',
            'last_30_days' => '30 Hari Terakhir',
            'this_year'    => 'Tahun Ini (' . now()->year . ')',
        ];
    }

    public function getServiceOptions(): array
    {
        return [
            'all'            => 'Semua Layanan',
            'with_driver'    => 'Dengan Sopir',
            'without_driver' => 'Lepas Kunci',
        ];
    }
}
