<?php

namespace App\Filament\Widgets;

use Filament\Facades\Filament;

use App\Models\Booking;
use App\Models\Car;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FleetUtilizationChart extends ChartWidget
{
    protected static ?string $heading = 'Okupansi Armada (30 Hari Terakhir)';
    
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = '30s';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $days = [];
        $utilizationRates = [];
        
        $totalCars = Car::query()->when(Filament::getTenant(), fn($q, $tenant) => $q->where('store_id', $tenant->id))->count() ?: 1; // Avoid division by zero

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('d M');

            // Count bookings that were active on this specific date
            $activeBookingsCount = Booking::query()->when(Filament::getTenant(), fn($q, $tenant) => $q->where('bookings.store_id', $tenant->id))->whereNotIn('booking_status', ['cancelled', 'rejected'])
                ->where('pickup_date', '<=', $date->endOfDay())
                ->where('return_date', '>=', $date->startOfDay())
                ->count();

            $rate = ($activeBookingsCount / $totalCars) * 100;
            $utilizationRates[] = round(min($rate, 100), 1);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Utilization Rate (%)',
                    'data' => $utilizationRates,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $days,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
