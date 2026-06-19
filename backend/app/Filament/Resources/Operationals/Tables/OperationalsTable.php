<?php

namespace App\Filament\Resources\Operationals\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class OperationalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking.booking_code')
                    ->searchable()
                    ->sortable()
                    ->label('Kode Booking')
                    ->fontFamily('mono')
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('car.car_name')
                    ->searchable()
                    ->sortable()
                    ->label('Armada')
                    ->description(fn($record) => $record->car->plate_number ?? ''),

                Tables\Columns\TextColumn::make('inspection_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pre_rental' => 'info',
                        'post_rental' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state === 'pre_rental' ? 'Sebelum Sewa' : 'Setelah Sewa')
                    ->label('Tipe Inspeksi'),

                Tables\Columns\TextColumn::make('inspector_name')
                    ->searchable()
                    ->sortable()
                    ->label('Inspektur'),

                Tables\Columns\TextColumn::make('odometer_km')
                    ->numeric()
                    ->suffix(' KM')
                    ->label('Odometer'),

                Tables\Columns\TextColumn::make('fuel_level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'full' => 'success',
                        'three_quarter' => 'info',
                        'half' => 'warning',
                        'quarter' => 'danger',
                        'empty' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'full' => 'Full',
                        'three_quarter' => '3/4',
                        'half' => '1/2',
                        'quarter' => '1/4',
                        'empty' => 'Kosong',
                        default => $state,
                    })
                    ->label('BBM'),

                Tables\Columns\IconColumn::make('damage_found')
                    ->boolean()
                    ->label('Kerusakan?'),

                Tables\Columns\TextColumn::make('damage_cost')
                    ->money('IDR')
                    ->sortable()
                    ->label('Biaya Kerusakan'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->label('Status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('inspection_type')
                    ->options([
                        'pre_rental' => 'Sebelum Sewa',
                        'post_rental' => 'Setelah Sewa',
                    ])
                    ->label('Tipe Inspeksi'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                    ])
                    ->label('Status'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
