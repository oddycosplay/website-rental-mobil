<?php

namespace App\Filament\Widgets;

use Filament\Facades\Filament;

use App\Models\Booking;
use App\Models\Expense;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class IncomeExpenseChart extends ChartWidget
{
    protected static ?string $heading = 'Ringkasan Keuangan (Tahun Ini)';

    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = '30s';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $months = collect(range(1, 12))->map(fn ($month) => now()->month($month)->format('M'));

        $incomeData = Booking::query()->when(Filament::getTenant(), fn($q, $tenant) => $q->where('bookings.store_id', $tenant->id))->where('payment_status', 'paid')
            ->whereYear('created_at', now()->year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(grand_total) as total'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $expenseData = Expense::query()->when(Filament::getTenant(), fn($q, $tenant) => $q->where('store_id', $tenant->id))->whereYear('date', now()->year)
            ->select(DB::raw('MONTH(date) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $formattedIncome = [];
        $formattedExpense = [];

        foreach (range(1, 12) as $month) {
            $formattedIncome[] = $incomeData[$month] ?? 0;
            $formattedExpense[] = $expenseData[$month] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => $formattedIncome,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $formattedExpense,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $months->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
