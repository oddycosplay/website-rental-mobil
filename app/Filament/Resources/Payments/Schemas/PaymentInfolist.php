<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;

class PaymentInfolist
{
    public static function configure(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Transaksi')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('payment_code')
                                    ->label('Kode Pembayaran')
                                    ->weight('bold')
                                    ->copyable(),
                                Infolists\Components\TextEntry::make('transaction_id')
                                    ->label('ID Transaksi (Midtrans)')
                                    ->placeholder('N/A'),
                                Infolists\Components\TextEntry::make('booking.booking_code')
                                    ->label('ID Pesanan')
                                    ->color('primary')
                                    ->url(fn ($record) => $record->booking_id ? "/admin/bookings/{$record->booking_id}" : null),
                            ]),
                        
                        Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('payment_status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'success' => 'success',
                                        'pending' => 'warning',
                                        'failed', 'expired' => 'danger',
                                        default => 'gray',
                                    }),
                                Infolists\Components\TextEntry::make('payment_method')
                                    ->label('Metode Pembayaran'),
                                Infolists\Components\TextEntry::make('payment_date')
                                    ->label('Waktu Pembayaran')
                                    ->dateTime()
                                    ->placeholder('Belum dibayar'),
                            ]),
                    ]),

                Section::make('Rincian Nominal')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('gross_amount')
                                    ->label('Total Tagihan')
                                    ->money('IDR'),
                                Infolists\Components\TextEntry::make('paid_amount')
                                    ->label('Jumlah yang Dibayar')
                                    ->money('IDR')
                                    ->weight('bold')
                                    ->color('success'),
                            ]),
                    ]),
            ]);
    }
}
