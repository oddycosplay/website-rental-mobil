<?php

namespace App\Filament\Resources\Cars\Tables;

use App\Models\Car;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

class CarsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->square(),
                
                Tables\Columns\TextColumn::make('car_name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Car $record): string => $record->plate_number),
                
                Tables\Columns\TextColumn::make('branch.name')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('daily_price')
                    ->money('IDR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'available' => 'success',
                        'rented' => 'warning',
                        'maintenance' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'available' => 'Tersedia',
                        'rented' => 'Disewa',
                        'maintenance' => 'Perawatan',
                        default => $state,
                    }),
                
                Tables\Columns\IconColumn::make('is_available')
                    ->boolean()
                    ->label('Web'),

                Tables\Columns\IconColumn::make('featured')
                    ->boolean()
                    ->label('Top'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('branch')
                    ->relationship('branch', 'name'),
                Tables\Filters\SelectFilter::make('status'),
            ])
            ->headerActions([
                CarExportAction::make(),
                CarExportAction::makePdf(),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    CarExportAction::makeBulkExcel(),
                    CarExportAction::makeBulkPdf(),
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
