<?php
 
namespace App\Filament\Widgets;

use Filament\Facades\Filament;

use App\Models\Booking;
use App\Models\Car;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
 
/**
 * @phpstan-import-type Stat from \Filament\Widgets\StatsOverviewWidget\Stat
 */
class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '15s';
 
    /** @return array<int, \Filament\Widgets\StatsOverviewWidget\Stat> */
    protected function getStats(): array
    {
        $monthlyRevenue = Booking::query()->when(Filament::getTenant(), fn($q, $tenant) => $q->where('bookings.store_id', $tenant->id))->where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('grand_total');
 
        // Revenue trend (last 7 days)
        $revenueTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $revenueTrend[] = (float) Booking::query()->when(Filament::getTenant(), fn($q, $tenant) => $q->where('bookings.store_id', $tenant->id))->where('payment_status', 'paid')
                ->whereDate('created_at', now()->subDays($i))
                ->sum('grand_total');
        }
 
        $onRentCount = Car::query()->when(Filament::getTenant(), fn($q, $tenant) => $q->where('store_id', $tenant->id))->where('status', 'rented')->count();
        $pendingBookings = Booking::query()->when(Filament::getTenant(), fn($q, $tenant) => $q->where('bookings.store_id', $tenant->id))->where('booking_status', 'pending')->count();
        $todayReturns = Booking::query()->when(Filament::getTenant(), fn($q, $tenant) => $q->where('bookings.store_id', $tenant->id))
            ->whereDate('return_date', now())
            ->whereIn('booking_status', ['confirmed', 'ongoing'])
            ->count();
 
        // Booking count trend (last 7 days)
        $bookingTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $bookingTrend[] = Booking::query()->when(Filament::getTenant(), fn($q, $tenant) => $q->where('bookings.store_id', $tenant->id))
                ->whereDate('created_at', now()->subDays($i))
                ->count();
        }
 
        /** @var Stat $revenueStat */
        $revenueStat = Stat::make('Revenue Bulan Ini', 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'));
        $revenueStat->description('Total lunas bulan berjalan');
        $revenueStat->descriptionIcon('heroicon-m-banknotes');
        $revenueStat->chart($revenueTrend);
        $revenueStat->color('success');
 
        /** @var Stat $rentStat */
        $rentStat = Stat::make('Kendaraan On Rent', $onRentCount);
        $rentStat->description('Unit sedang disewa');
        $rentStat->descriptionIcon('heroicon-m-truck');
        $rentStat->chart([7, 3, 4, 5, 6, 3, 5]); // Placeholder for visuals
        $rentStat->color('warning');
 
        /** @var Stat $returnStat */
        $returnStat = Stat::make('Pengembalian Hari Ini', $todayReturns);
        $returnStat->description('Unit jatuh tempo hari ini');
        $returnStat->descriptionIcon('heroicon-m-arrow-path');
        $returnStat->color($todayReturns > 0 ? 'info' : 'gray');
 
        /** @var Stat $pendingStat */
        $pendingStat = Stat::make('Booking Pending', $pendingBookings);
        $pendingStat->description('Butuh approval admin');
        $pendingStat->descriptionIcon('heroicon-m-clock');
        $pendingStat->chart($bookingTrend);
        $pendingStat->color($pendingBookings > 0 ? 'danger' : 'success');
 
        $avgDuration = Booking::query()->when(Filament::getTenant(), fn($q, $tenant) => $q->where('bookings.store_id', $tenant->id))->where('booking_status', 'completed')->avg('total_day') ?? 0;
 
        /** @var Stat $avgStat */
        $avgStat = Stat::make('Rata-rata Durasi', round($avgDuration, 1) . ' Hari');
        $avgStat->description('Durasi sewa per booking');
        $avgStat->descriptionIcon('heroicon-m-clock');
        $avgStat->color('primary');
 
        return [
            $revenueStat,
            $rentStat,
            $returnStat,
            $pendingStat,
            $avgStat,
        ];
    }
}
