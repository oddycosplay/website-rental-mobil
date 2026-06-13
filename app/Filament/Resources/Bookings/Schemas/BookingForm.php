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
use Illuminate\Support\Str;


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
                                                'grab_gojek' => 'Kirim via Ojek Online (Grab/Gojek/dll)',
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
                                                'grab_gojek' => 'Dijemput via Ojek Online (Grab/Gojek/dll)',
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

                                Select::make('rental_category')
                                    ->label('Kategori Rental')
                                    ->options([
                                        'pribadi' => 'Pribadi',
                                        'perusahaan' => 'Perusahaan',
                                    ])
                                    ->default('pribadi')
                                    ->required()
                                    ->live()
                                    ->native(false),

                                Select::make('rental_type')
                                    ->label('Tipe Rental')
                                    ->options([
                                        'daily' => 'Daily',
                                        'monthly' => 'Monthly',
                                    ])
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                                        $carId = $get('car_id');
                                        if ($carId) {
                                            $car = \App\Models\Car::query()->find($carId);
                                            if ($car) {
                                                $price = $state === 'monthly' ? $car->monthly_price : $car->daily_price;
                                                $set('grand_total', $price);
                                            }
                                        }
                                    })
                                    ->native(false),
                                TextInput::make('booking_code')
                                    ->label('Kode Booking')
                                    ->default(fn () => 'TRX-' . strtoupper(Str::random(8)))
                                    ->disabled()
                                    ->dehydrated()
                                    ->unique(ignoreRecord: true),
                            ])->columnSpan(1),
                        Section::make('Armada & Sopir')
                            ->schema([
                                Select::make('car_id')
                                    ->label('Pilih Armada')
                                    ->relationship('car', 'car_name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function (?string $state, Set $set, Get $get) {
                                        if ($state) {
                                            $car = \App\Models\Car::query()->find($state);
                                            if ($car) {
                                                $rentalType = $get('rental_type') ?? 'daily';
                                                $price = $rentalType === 'monthly' ? $car->monthly_price : $car->daily_price;
                                                $set('grand_total', $price);
                                            }
                                        }
                                    }),

                                Toggle::make('is_new_customer')
                                    ->label('Kustomer Baru (New User)')
                                    ->live()
                                    ->helperText('Aktifkan untuk mendaftarkan kustomer baru secara langsung'),

                                Select::make('customer_id')
                                    ->label('Pilih Pelanggan')
                                    ->relationship('customer', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->hidden(fn(Get $get) => $get('is_new_customer'))
                                    ->required(fn(Get $get) => !$get('is_new_customer')),

                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('new_customer_name')
                                            ->label('Nama Kustomer Baru')
                                            ->required(fn(Get $get) => $get('is_new_customer')),
                                        TextInput::make('new_customer_email')
                                            ->label('Email Kustomer Baru')
                                            ->email()
                                            ->required(fn(Get $get) => $get('is_new_customer')),
                                        TextInput::make('new_customer_phone')
                                            ->label('No Telepon Kustomer Baru')
                                            ->required(fn(Get $get) => $get('is_new_customer')),
                                        TextInput::make('new_customer_nik')
                                            ->label('NIK (KTP) Kustomer Baru')
                                            ->required(fn(Get $get) => $get('is_new_customer')),
                                        Textarea::make('new_customer_address')
                                            ->label('Alamat Kustomer Baru')
                                            ->columnSpanFull()
                                            ->required(fn(Get $get) => $get('is_new_customer')),
                                    ])
                                    ->visible(fn(Get $get) => $get('is_new_customer')),

                                Toggle::make('with_driver')
                                    ->label('Gunakan Sopir')
                                    ->live(),

                                Grid::make(2)
                                    ->schema([
                                        Select::make('driver_id')
                                            ->label('Pilih Sopir Registered (Opsional)')
                                            ->searchable()
                                            ->preload()
                                            ->options(function (Get $get) {
                                                if (! $get('with_driver')) {
                                                    return [];
                                                }
                                                return Driver::active()->pluck('name', 'id');
                                            })
                                            ->live()
                                            ->afterStateUpdated(function (?string $state, Set $set) {
                                                if ($state) {
                                                    $driver = Driver::find((int) $state, ['id', 'name']);
                                                    if ($driver) {
                                                        $set('driver_name', $driver->name);
                                                    }
                                                }
                                            }),
                                        TextInput::make('driver_name')
                                            ->label('Nama Sopir')
                                            ->required(fn(Get $get) => $get('with_driver'))
                                            ->placeholder('Masukkan nama sopir...'),
                                    ])
                                    ->visible(fn(Get $get) => $get('with_driver')),
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
                                    ->required()
                                    ->disabled(fn (Get $get) => $get('rental_category') !== 'perusahaan')
                                    ->dehydrated(),

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
