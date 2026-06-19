<?php

namespace App\Filament\Widgets;

use Filament\Facades\Filament;
use App\Models\Expense;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestExpenses extends BaseWidget
{
    protected static ?string $heading = 'Pengeluaran Operasional';
    protected static ?int $sort = 13;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Expense::query()
                    ->when(Filament::getTenant(), fn($q, $tenant) => $q->where('store_id', $tenant->id))
                    ->latest('date')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->weight('semibold'),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->description(fn($record) => $record->reference_number)
                    ->wrap(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->color('danger')
                    ->weight('bold')
                    ->alignment('right'),
            ])
            ->paginated(false);
    }
}
