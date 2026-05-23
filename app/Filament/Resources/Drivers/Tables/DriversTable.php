<?php

namespace App\Filament\Resources\Drivers\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

class DriversTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular()
                    ->disk('public'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pengemudi')
                    ->icon('heroicon-m-user')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->icon('heroicon-m-phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('license_number')
                    ->label('No. SIM')
                    ->icon('heroicon-m-identification')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($record, $state) => $record->is_busy ? 'busy' : $state)
                    ->color(fn (string $state): string => match ($state) {
                        'available', 'active' => 'success',
                        'busy' => 'warning',
                        'off' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'available', 'active' => 'heroicon-m-check-circle',
                        'busy' => 'heroicon-m-arrow-path',
                        'off' => 'heroicon-m-minus-circle',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->description(fn ($record, string $state): string => match ($record->is_busy ? 'busy' : $state) {
                        'available', 'active' => 'Tersedia',
                        'busy' => 'Sedang Bertugas',
                        'off' => 'Libur',
                        default => ucfirst($state),
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'available' => 'Tersedia',
                        'busy' => 'Bertugas',
                        'off' => 'Libur',
                    ]),
            ])
            ->headerActions([
                DriverExportAction::make(),
                DriverExportAction::makePdf(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DriverExportAction::makeBulkExcel(),
                    DriverExportAction::makeBulkPdf(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
