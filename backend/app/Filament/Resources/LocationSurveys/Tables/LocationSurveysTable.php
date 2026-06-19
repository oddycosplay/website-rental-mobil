<?php

namespace App\Filament\Resources\LocationSurveys\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class LocationSurveysTable
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

                Tables\Columns\TextColumn::make('booking.customer.name')
                    ->searchable()
                    ->sortable()
                    ->label('Customer'),

                Tables\Columns\TextColumn::make('surveyor_name')
                    ->searchable()
                    ->sortable()
                    ->label('Surveyor'),

                Tables\Columns\TextColumn::make('survey_date')
                    ->date('d M Y')
                    ->sortable()
                    ->label('Tanggal Survei'),

                Tables\Columns\TextColumn::make('survey_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'delivery' => 'info',
                        'pickup' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state === 'delivery' ? 'Delivery' : 'Pickup')
                    ->label('Tipe Survei'),

                Tables\Columns\TextColumn::make('recommendation')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'layak' => 'success',
                        'tidak_layak' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'layak' => 'Layak',
                        'tidak_layak' => 'Tidak Layak',
                        default => $state,
                    })
                    ->label('Rekomendasi'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'approved' => 'Disetujui',
                        'pending' => 'Pending',
                        'rejected' => 'Ditolak',
                        default => ucfirst($state),
                    })
                    ->label('Status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('recommendation')
                    ->options([
                        'layak' => 'Layak',
                        'tidak_layak' => 'Tidak Layak',
                    ])
                    ->label('Rekomendasi'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
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
