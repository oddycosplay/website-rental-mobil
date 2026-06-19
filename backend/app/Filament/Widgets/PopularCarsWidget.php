<?php

namespace App\Filament\Widgets;

use App\Models\Car;
use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PopularCarsWidget extends BaseWidget
{
    protected static ?string $heading = 'Armada Terpopuler';
    protected static ?int $sort = 11;
    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Car::query()
                    ->when(Filament::getTenant(), fn($q, $tenant) => $q->where('store_id', $tenant->id))
                    ->withCount('bookings')
                    ->orderByDesc('bookings_count')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('')
                    ->circular()
                    ->disk('public')
                    ->size(40),
                Tables\Columns\TextColumn::make('car_name')
                    ->label('Armada')
                    ->weight('bold')
                    ->description(fn($record) => $record->brand->name),
                Tables\Columns\TextColumn::make('bookings_count')
                    ->label('Total Sewa')
                    ->badge()
                    ->color('primary')
                    ->weight('bold')
                    ->suffix(' kali'),
            ])
            ->paginated(false);
    }
}
