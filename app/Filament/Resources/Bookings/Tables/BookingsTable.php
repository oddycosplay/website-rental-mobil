<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\Bookings\Tables\ExportAction;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('car.thumbnail')
                    ->label('')
                    ->circular()
                    ->disk('public')
                    ->size(40),

                Tables\Columns\TextColumn::make('booking_code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->label('ID Pesanan')
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->description(fn ($record) => $record->branch->name ?? 'Cabang Utama'),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->label('Pelanggan')
                    ->weight('semibold')
                    ->description(fn($record) => $record->user->phone ?? '-'),
                
                Tables\Columns\TextColumn::make('car.car_name')
                    ->searchable()
                    ->sortable()
                    ->label('Armada')
                    ->description(fn($record) => ($record->car->brand->name ?? '') . ' - ' . ($record->car->plate_number ?? '')),
                
                Tables\Columns\TextColumn::make('rental_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'daily' => 'info',
                        'monthly' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->label('Tipe Rental'),

                Tables\Columns\TextColumn::make('with_driver')
                    ->label('Layanan')
                    ->formatStateUsing(fn ($state) => $state ? 'Driver' : 'Lepas Kunci')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'warning'),
                
                Tables\Columns\TextColumn::make('pickup_date')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->label('Jadwal')
                    ->description(fn ($record) => 'Hingga: ' . $record->return_date->format('d M Y')),

                Tables\Columns\TextColumn::make('grand_total')
                    ->money('IDR')
                    ->sortable()
                    ->label('Total Biaya')
                    ->color('success')
                    ->weight('bold')
                    ->alignment('right'),

                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'partial' => 'warning',
                        'unpaid' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    })
                    ->label('Pembayaran'),

                Tables\Columns\SelectColumn::make('booking_status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'expired' => 'Expired',
                    ])
                    ->label('Status Operasional'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'unpaid' => 'Belum Bayar',
                        'paid' => 'Lunas',
                    ]),
                Tables\Filters\SelectFilter::make('booking_status'),
            ])
            ->headerActions([
                ExportAction::make(),
                ExportAction::makePdf(),
            ])
            ->actions([
                \Filament\Tables\Actions\ViewAction::make(),
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\Action::make('processReturn')
                    ->label('Proses Pengembalian')
                    ->icon('heroicon-o-arrow-left-on-rectangle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->booking_status === 'ongoing')
                    ->action(function ($record) {
                        $record->update([
                            'booking_status' => 'completed',
                        ]);
                        
                        $record->refresh();
                        
                        $lateFeeText = $record->late_fee > 0 
                            ? " dengan denda telat Rp " . number_format($record->late_fee, 0, ',', '.')
                            : " tanpa denda telat";

                        \Filament\Notifications\Notification::make()
                            ->title('Pengembalian Berhasil')
                            ->body("Mobil telah berhasil dikembalikan" . $lateFeeText . ".")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    ExportAction::makeBulk(),
                    ExportAction::makeBulkPdf(),
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
