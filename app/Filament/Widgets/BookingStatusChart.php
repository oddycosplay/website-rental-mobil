<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class BookingStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Status Pesanan (Keseluruhan)';
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $data = Booking::query()
            ->when(Filament::getTenant(), fn($q, $tenant) => $q->where('bookings.store_id', $tenant->id))
            ->select('booking_status', DB::raw('count(*) as total'))
            ->groupBy('booking_status')
            ->get();

        $colors = [
            'pending' => '#f59e0b',
            'confirmed' => '#3b82f6',
            'ongoing' => '#8b5cf6',
            'completed' => '#10b981',
            'cancelled' => '#ef4444',
            'expired' => '#6b7280',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Status Booking',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => $data->map(fn($item) => $colors[$item->booking_status] ?? '#cbd5e1')->toArray(),
                ],
            ],
            'labels' => $data->map(fn($item) => ucfirst($item->booking_status))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
