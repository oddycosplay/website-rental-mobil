<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Driver;
use App\Models\Booking;

class BookingForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make('Informasi Waktu & Lokasi')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        DateTimePicker::make('pickup_date')
                                            ->label('Waktu Penjemputan')
                                            ->required()
                                            ->live(),
                                        DateTimePicker::make('return_date')
                                            ->label('Waktu Pengembalian')
                                            ->required()
                                            ->after('pickup_date')
                                            ->live(),
                                    ]),

                                Grid::make(2)
                                    ->schema([
                                        Select::make('delivery_type')
                                            ->label('Opsi Pengantaran')
                                            ->options([
                                                'none' => 'Ambil Sendiri di Garasi',
                                                'standard' => 'Diantar ke Lokasi (Radius 10km+)',
                                                'airport' => 'Diantar ke Bandara (Airport)',
                                            ])
                                            ->default('none')
                                            ->native(false)
                                            ->required(),
                                        Select::make('pickup_type')
                                            ->label('Opsi Penjemputan')
                                            ->options([
                                                'none' => 'Kembalikan Sendiri ke Garasi',
                                                'standard' => 'Dijemput di Lokasi (Radius 10km+)',
                                                'airport' => 'Dijemput di Bandara (Airport)',
                                            ])
                                            ->default('none')
                                            ->native(false)
                                            ->required(),
                                    ]),

                                Textarea::make('pickup_location')
                                    ->label('Alamat Pengantaran / Penjemputan Awal')
                                    ->rows(2),
                                Textarea::make('return_location')
                                    ->label('Alamat Penjemputan / Pengembalian Akhir')
                                    ->rows(2),
                            ])->columnSpan(2),

                        Section::make('Status Pesanan')
                            ->schema([
                                Select::make('booking_status')
                                    ->label('Status Operasional')
                                    ->options([
                                        'pending' => 'Pending',
                                        'confirmed' => 'Confirmed',
                                        'ongoing' => 'Ongoing',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                        'rejected' => 'Rejected',
                                    ])
                                    ->required()
                                    ->native(false),
                                Select::make('payment_status')
                                    ->label('Status Pembayaran')
                                    ->options([
                                        'unpaid' => 'Belum Bayar',
                                        'partial' => 'Bayar Sebagian',
                                        'paid' => 'Lunas',
                                        'refunded' => 'Refund',
                                    ])
                                    ->required()
                                    ->native(false),

                                Select::make('rental_type')
                                    ->label('Tipe Rental')
                                    ->options([
                                        'daily' => 'Daily',
                                        'monthly' => 'Monthly',
                                    ])
                                    ->required()
                                    ->native(false),
                                TextInput::make('booking_code')
                                    ->label('Kode Booking')
                                    ->disabled()
                                    ->dehydrated(),
                            ])->columnSpan(1),
                        Section::make('Armada & Sopir')
                            ->schema([
                                Select::make('car_id')
                                    ->label('Pilih Armada')
                                    ->relationship('car', 'car_name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),

                                Select::make('user_id')
                                    ->label('Pilih Pelanggan')
                                    ->relationship('user', 'name', fn ($query) => $query->whereHas('roles', fn ($q) => $q->where('name', 'customer')))
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Toggle::make('with_driver')
                                    ->label('Gunakan Sopir')
                                    ->live(),
                                Select::make('driver_id')
                                    ->label('Pilih Sopir')
                                    ->relationship('driver', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->visible(fn(Get $get) => $get('with_driver'))
                                    ->options(function (Get $get) {
                                        if (! $get('with_driver')) {
                                            return [];
                                        }

                                        return Driver::active()->pluck('name', 'id');
                                    })
                                    ->required(fn(Get $get) => $get('with_driver'))
                                    ->disabled(fn(Get $get) => ! $get('with_driver'))
                                    ->hint('Wajib jika "Gunakan Sopir" aktif'),
                            ])->columnSpan(2),
                        Section::make('Rincian Biaya')
                            ->schema([
                                TextInput::make('delivery_fee')
                                    ->label('Biaya Pengantaran')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),

                                TextInput::make('pickup_fee')
                                    ->label('Biaya Penjemputan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),

                                TextInput::make('ojol_fee')
                                    ->label('Biaya Ojol')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),

                                TextInput::make('grand_total')
                                    ->label('Total Bayar')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),

                                TextInput::make('discount')
                                    ->label('Diskon')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),

                                Textarea::make('notes')
                                    ->label('Catatan Internal')
                                    ->rows(3),
                            ])->columnSpan(1),
                    ]),
            ]);
    }
}
