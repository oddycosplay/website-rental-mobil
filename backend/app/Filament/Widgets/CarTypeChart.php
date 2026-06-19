<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CarTypeChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Tipe Kendaraan (Booking)';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $data = Booking::query()
            ->when(Filament::getTenant(), fn($q, $tenant) => $q->where('bookings.store_id', $tenant->id))
            ->join('cars', 'bookings.car_id', '=', 'cars.id')
            ->select('cars.type_name as name', DB::raw('count(*) as total'))
            ->groupBy('cars.type_name')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Booking',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#f43f5e'
                    ],
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
