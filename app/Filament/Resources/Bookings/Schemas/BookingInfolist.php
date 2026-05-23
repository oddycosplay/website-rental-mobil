<?php
namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Group;

class BookingInfolist
{
    public static function configure(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(3)
                    ->schema([
                        Group::make()
                            ->schema([
                                Section::make('Informasi Armada & Pelanggan')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                ImageEntry::make('car.thumbnail')
                                                    ->label('')
                                                    ->disk('public')
                                                    ->circular()
                                                    ->size(80),
                                                Group::make()
                                                    ->schema([
                                                        TextEntry::make('car.car_name')
                                                            ->label('Unit Kendaraan')
                                                            ->weight('bold')
                                                            ->size('lg'),
                                                        TextEntry::make('car.plate_number')
                                                            ->label('Nomor Polisi')
                                                            ->fontFamily('mono'),
                                                    ]),
                                            ]),
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('customer.name')
                                                    ->label('Nama Pelanggan')
                                                    ->icon('heroicon-m-user'),
                                                TextEntry::make('customer.phone')
                                                    ->label('No. Telepon')
                                                    ->icon('heroicon-m-phone'),
                                            ]),
                                    ]),

                                Section::make('Detail Perjalanan')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('pickup_date')
                                                    ->label('Penjemputan')
                                                    ->dateTime('d M Y H:i')
                                                    ->icon('heroicon-m-calendar'),
                                                TextEntry::make('return_date')
                                                    ->label('Pengembalian')
                                                    ->dateTime('d M Y H:i')
                                                    ->icon('heroicon-m-calendar-days'),
                                            ]),
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('delivery_type')
                                                    ->label('Opsi Pengantaran')
                                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                                        'none' => 'Ambil Sendiri di Garasi',
                                                        'standard' => 'Diantar ke Lokasi (Radius 10km+)',
                                                        'airport' => 'Diantar ke Bandara (Airport)',
                                                        default => $state,
                                                    })
                                                    ->icon('heroicon-m-truck'),
                                                TextEntry::make('pickup_type')
                                                    ->label('Opsi Penjemputan')
                                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                                        'none' => 'Kembalikan Sendiri ke Garasi',
                                                        'standard' => 'Dijemput di Lokasi (Radius 10km+)',
                                                        'airport' => 'Dijemput di Bandara (Airport)',
                                                        default => $state,
                                                    })
                                                    ->icon('heroicon-m-arrow-path'),
                                            ]),
                                        TextEntry::make('pickup_location')
                                            ->label('Alamat Pengantaran / Penjemputan Awal')
                                            ->icon('heroicon-m-map-pin'),
                                        TextEntry::make('return_location')
                                            ->label('Alamat Penjemputan / Pengembalian Akhir')
                                            ->icon('heroicon-m-map'),
                                    ]),
                            ])
                            ->columnSpan(2),

                        Group::make()
                            ->schema([
                                Section::make('Status & Pembayaran')
                                    ->schema([
                                        TextEntry::make('booking_code')
                                            ->label('Kode Pesanan')
                                            ->weight('bold')
                                            ->copyable(),
                                        TextEntry::make('booking_status')
                                            ->label('Status Operasional')
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'ongoing' => 'primary',
                                                'completed' => 'success',
                                                'cancelled', 'rejected' => 'danger',
                                                default => 'gray',
                                            }),
                                        TextEntry::make('payment_status')
                                            ->label('Status Pembayaran')
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'paid' => 'success',
                                                'partial' => 'warning',
                                                'unpaid' => 'danger',
                                                'refunded' => 'info',
                                                default => 'gray',
                                            }),
                                    ]),

                                Section::make('Rincian Biaya')
                                    ->schema([
                                        TextEntry::make('delivery_fee')
                                            ->label('Biaya Pengantaran')
                                            ->money('IDR'),
                                        TextEntry::make('pickup_fee')
                                            ->label('Biaya Penjemputan')
                                            ->money('IDR'),
                                        TextEntry::make('ojol_fee')
                                            ->label('Biaya Ojol')
                                            ->money('IDR'),
                                        TextEntry::make('grand_total')
                                            ->label('Total Transaksi')
                                            ->money('IDR')
                                            ->weight('bold')
                                            ->size('lg')
                                            ->color('success'),
                                        TextEntry::make('dp_amount')
                                            ->label('Uang Muka (DP)')
                                            ->money('IDR'),
                                        TextEntry::make('remaining_payment')
                                            ->label('Sisa Tagihan')
                                            ->money('IDR')
                                            ->color('danger'),
                                        TextEntry::make('notes')
                                            ->label('Catatan Tambahan')
                                            ->placeholder('Tidak ada catatan.'),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
