<?php

namespace App\Filament\Widgets;

use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopCustomersWidget extends BaseWidget
{
    protected static ?string $heading = 'Pelanggan Terbaik';
    protected static ?int $sort = 12;
    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\User::query()
                    ->whereHas('roles', function($q) {
                        $q->where('name', 'customer');
                    })
                    ->withSum(['bookings' => function($query) {
                        $query->where('payment_status', 'paid');
                    }], 'grand_total')
                    ->orderByDesc('bookings_sum_grand_total')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Pelanggan')
                    ->weight('bold')
                    ->description(fn($record) => $record->phone),
                Tables\Columns\TextColumn::make('bookings_count')
                    ->counts('bookings')
                    ->label('Transaksi')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('bookings_sum_grand_total')
                    ->label('Lifetime Value')
                    ->money('IDR')
                    ->weight('bold')
                    ->color('success'),
            ])
            ->paginated(false);
    }
}
