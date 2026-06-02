<?php

namespace App\Filament\Resources\Operational\VehicleInspections\Tables;

use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class VehicleInspectionTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking.booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('car.car_name')
                    ->label('Kendaraan')
                    ->searchable()
                    ->description(fn ($record) => $record->car?->plate_number ?? '-'),

                Tables\Columns\TextColumn::make('inspection_type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pre_rental'  => 'Cek Keluar',
                        'post_rental' => 'Cek Masuk',
                        default       => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'pre_rental'  => 'info',
                        'post_rental' => 'success',
                        default       => 'gray',
                    }),

                Tables\Columns\TextColumn::make('inspector_name')
                    ->label('Petugas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('inspected_at')
                    ->label('Waktu Cek')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('odometer_km')
                    ->label('Odometer')
                    ->suffix(' km')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fuel_level')
                    ->label('BBM')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'full'          => 'Full',
                        'three_quarter' => '¾',
                        'half'          => '½',
                        'quarter'       => '¼',
                        'empty'         => 'Kosong',
                        default         => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'full', 'three_quarter' => 'success',
                        'half'                  => 'warning',
                        'quarter', 'empty'      => 'danger',
                        default                 => 'gray',
                    }),

                Tables\Columns\IconColumn::make('damage_found')
                    ->label('Kerusakan?')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success'),

                Tables\Columns\TextColumn::make('damage_cost')
                    ->label('Denda Kerusakan')
                    ->money('IDR')
                    ->color('danger')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('dirty_fine')
                    ->label('Denda Mobil Kotor')
                    ->money('IDR')
                    ->color('danger')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('fuel_fine')
                    ->label('Denda BBM')
                    ->money('IDR')
                    ->color('danger')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'draft'     => 'Draft',
                        'submitted' => 'Menunggu Review',
                        'approved'  => 'Disetujui',
                        default     => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'draft'     => 'gray',
                        'submitted' => 'warning',
                        'approved'  => 'success',
                        default     => 'gray',
                    }),
            ])
            ->defaultSort('inspected_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('inspection_type')
                    ->label('Tipe Pengecekan')
                    ->options([
                        'pre_rental'  => 'Pengecekan Keluar',
                        'post_rental' => 'Pengecekan Masuk',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft'     => 'Draft',
                        'submitted' => 'Menunggu Review',
                        'approved'  => 'Disetujui',
                    ]),

                Tables\Filters\TernaryFilter::make('damage_found')
                    ->label('Ada Kerusakan')
                    ->trueLabel('Ada Kerusakan')
                    ->falseLabel('Tidak Ada Kerusakan'),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'submitted')
                    ->action(function ($record) {
                        $record->update(['status' => 'approved']);
                        
                        $booking = $record->booking;
                        $car = $record->car;

                        if ($record->inspection_type === 'pre_rental') {
                            if ($booking) {
                                $booking->update([
                                    'booking_status' => 'ongoing',
                                    'notes' => 'Pengecekan keluar disetujui. Mobil siap digunakan oleh pelanggan.',
                                ]);
                            }
                            if ($car) {
                                $car->update(['status' => 'rented']);
                            }
                        } elseif ($record->inspection_type === 'post_rental') {
                            if ($booking) {
                                $booking->update([
                                    'booking_status' => 'completed',
                                    'notes' => 'Pengecekan masuk disetujui. Mobil telah dikembalikan.',
                                ]);
                            }
                            if ($car) {
                                if ($record->damage_found || $record->damage_cost > 0) {
                                    $car->update(['status' => 'maintenance']);
                                } else {
                                    $car->update(['status' => 'available']);
                                }
                            }
                        }

                        Notification::make()
                            ->title('Pengecekan disetujui')
                            ->body('Status Booking dan Kendaraan telah diperbarui otomatis.')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->emptyStateHeading('Belum ada data pengecekan')
            ->emptyStateDescription('Form pengecekan kendaraan akan muncul di sini setelah petugas mengisi laporan.');
    }
}
