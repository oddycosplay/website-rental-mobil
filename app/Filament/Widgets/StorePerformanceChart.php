<?php
 
namespace App\Filament\Widgets;
 
use App\Models\Booking;
use App\Models\Store;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
 
class StorePerformanceChart extends ChartWidget
{
    protected static ?string $heading = 'Performa Pendapatan per Toko';
 
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = '30s';
    protected static ?string $maxHeight = '300px';
 
    public static function canView(): bool
    {
        return true;
    }
 
    protected function getData(): array
    {
        $data = Booking::where('payment_status', 'paid')
            ->join('cars', 'bookings.car_id', '=', 'cars.id')
            ->join('stores', 'cars.store_id', '=', 'stores.id')
            ->select('stores.name', DB::raw('SUM(bookings.grand_total) as total'))
            ->groupBy('stores.name')
            ->orderByDesc('total')
            ->get();
 
        return [
            'datasets' => [
                [
                    'label' => 'Total Pendapatan (IDR)',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'
                    ],
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }
 
    protected function getType(): string
    {
        return 'bar';
    }
}
