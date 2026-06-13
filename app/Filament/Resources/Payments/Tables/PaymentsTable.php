<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\Payments\Tables\PaymentExportAction;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_code')
                    ->label('Kode')
                    ->icon('heroicon-m-hashtag')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking.booking_code')
                    ->label('ID Pesanan')
                    ->icon('heroicon-m-shopping-cart')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode')
                    ->icon('heroicon-m-credit-card'),
                Tables\Columns\TextColumn::make('gross_amount')
                    ->label('Total')
                    ->icon('heroicon-m-banknotes')
                    ->money('IDR')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status Transaksi')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'success' => 'Berhasil',
                        'pending' => 'Pending',
                        'failed' => 'Gagal',
                        'expired' => 'Kedaluwarsa',
                        'refund' => 'Refund',
                        default => ucfirst($state),
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'success' => 'heroicon-m-check-circle',
                        'pending' => 'heroicon-m-clock',
                        'failed', 'expired' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'pending' => 'warning',
                        'failed', 'expired' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('booking.payment_status')
                    ->label('Status Tagihan')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'paid' => 'Sudah Bayar',
                        'partial' => 'Sudah DP',
                        'unpaid' => 'Belum Bayar',
                        'refunded' => 'Refund',
                        default => ucfirst($state),
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'paid' => 'heroicon-m-check-badge',
                        'partial' => 'heroicon-m-banknotes',
                        'unpaid' => 'heroicon-m-x-circle',
                        'refunded' => 'heroicon-m-arrow-path',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'partial' => 'info',
                        'unpaid' => 'danger',
                        'refunded' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Waktu')
                    ->icon('heroicon-m-clock')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'success' => 'Berhasil',
                        'failed' => 'Gagal',
                    ]),
                Tables\Filters\SelectFilter::make('booking.payment_status')
                    ->label('Filter Status Tagihan')
                    ->options([
                        'paid' => 'Sudah Bayar',
                        'partial' => 'Sudah DP',
                        'unpaid' => 'Belum Bayar',
                    ]),
            ])
            ->headerActions([
                PaymentExportAction::make(),
            ])
            ->actions([
                \Filament\Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    PaymentExportAction::makeBulk(),
                ]),
            ]);
    }
}
