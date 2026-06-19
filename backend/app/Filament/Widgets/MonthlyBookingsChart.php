<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Facades\Filament;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonthlyBookingsChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Jumlah Pesanan (Tahun Ini)';
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $months = collect(range(1, 12))->map(fn ($month) => Carbon::create(now()->year, $month)->format('M'));

        $data = Booking::query()
            ->when(Filament::getTenant(), fn($q, $tenant) => $q->where('bookings.store_id', $tenant->id))
            ->whereYear('created_at', now()->year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $formattedData = [];
        foreach (range(1, 12) as $month) {
            $formattedData[] = $data[$month] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pesanan',
                    'data' => $formattedData,
                    'backgroundColor' => '#6366f1',
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $months->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
