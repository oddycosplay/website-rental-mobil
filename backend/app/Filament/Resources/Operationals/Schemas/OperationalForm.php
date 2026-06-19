<?php

namespace App\Filament\Resources\Operationals\Schemas;

use App\Models\Booking;
use App\Models\Car;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;

class OperationalForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make('Informasi Utama')
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('store_id')
                                            ->label('Cabang')
                                            ->options(Store::all()->pluck('name', 'id'))
                                            ->searchable()
                                            ->required(),

                                        Forms\Components\Select::make('booking_id')
                                            ->label('Pesanan Booking')
                                            ->options(Booking::all()->pluck('booking_code', 'id'))
                                            ->searchable()
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function (callable $set, $state) {
                                                $booking = Booking::find($state);
                                                if ($booking) {
                                                    $set('car_id', $booking->car_id);
                                                    $set('store_id', $booking->store_id);
                                                }
                                            }),

                                        Forms\Components\Select::make('car_id')
                                            ->label('Armada Mobil')
                                            ->options(Car::all()->pluck('car_name', 'id'))
                                            ->searchable()
                                            ->required(),

                                        Forms\Components\Select::make('inspection_type')
                                            ->label('Tipe Inspeksi')
                                            ->options([
                                                'pre_rental' => 'Sebelum Sewa (Pre-Rental)',
                                                'post_rental' => 'Setelah Sewa (Post-Rental)',
                                            ])
                                            ->required(),

                                        Forms\Components\TextInput::make('inspector_name')
                                            ->label('Nama Inspektur')
                                            ->required()
                                            ->maxLength(255),

                                        Forms\Components\DateTimePicker::make('inspected_at')
                                            ->label('Waktu Inspeksi')
                                            ->default(now())
                                            ->required(),
                                    ]),
                            ]),

                        Forms\Components\Section::make('Status Verifikasi')
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Persetujuan Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'approved' => 'Approved',
                                    ])
                                    ->default('pending')
                                    ->required(),

                                Forms\Components\Textarea::make('notes')
                                    ->label('Catatan Inspektur')
                                    ->rows(4),
                            ]),
                    ]),

                Forms\Components\Section::make('Detail Kelayakan & Fisik Kendaraan')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('odometer_km')
                                    ->label('Odometer (KM)')
                                    ->numeric()
                                    ->required()
                                    ->suffix(' KM'),

                                Forms\Components\Select::make('fuel_level')
                                    ->label('Level BBM')
                                    ->options([
                                        'full' => 'Full',
                                        'three_quarter' => '3/4 (Three Quarter)',
                                        'half' => '1/2 (Half)',
                                        'quarter' => '1/4 (Quarter)',
                                        'empty' => 'Kosong (Empty)',
                                    ])
                                    ->required(),
                            ]),

                        Forms\Components\Grid::make(4)
                            ->schema([
                                Forms\Components\KeyValue::make('exterior')
                                    ->label('Checklist Eksterior')
                                    ->valueLabel('Kondisi / Catatan')
                                    ->keyLabel('Komponen')
                                    ->default([
                                        'Bumper Depan' => 'Mulus',
                                        'Bumper Belakang' => 'Mulus',
                                        'Kaca Depan' => 'Bagus',
                                        'Lampu Utama' => 'Berfungsi',
                                    ]),

                                Forms\Components\KeyValue::make('interior')
                                    ->label('Checklist Interior')
                                    ->valueLabel('Kondisi / Catatan')
                                    ->keyLabel('Komponen')
                                    ->default([
                                        'AC' => 'Dingin',
                                        'Jok Kursi' => 'Bersih',
                                        'Audio / Headunit' => 'Berfungsi',
                                        'Dashboard' => 'Rapi',
                                    ]),

                                Forms\Components\KeyValue::make('equipment')
                                    ->label('Peralatan & Surat')
                                    ->valueLabel('Kondisi / Catatan')
                                    ->keyLabel('Komponen')
                                    ->default([
                                        'STNK' => 'Ada',
                                        'Kunci Ganda' => 'Ada',
                                        'Dongkrak' => 'Ada',
                                        'Ban Serep' => 'Ada',
                                    ]),

                                Forms\Components\KeyValue::make('engine')
                                    ->label('Checklist Mesin')
                                    ->valueLabel('Kondisi / Catatan')
                                    ->keyLabel('Komponen')
                                    ->default([
                                        'Suara Mesin' => 'Halus',
                                        'Oli Mesin' => 'Cukup',
                                        'Air Radiator' => 'Cukup',
                                        'Kelistrikan Aki' => 'Bagus',
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Foto Fisik & BBM')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('photos')
                                    ->label('Foto Fisik Mobil')
                                    ->multiple()
                                    ->disk('public')
                                    ->directory('operational-photos')
                                    ->image(),

                                Forms\Components\FileUpload::make('fuel_photos')
                                    ->label('Foto Indikator BBM')
                                    ->multiple()
                                    ->disk('public')
                                    ->directory('operational-fuel-photos')
                                    ->image(),
                            ]),
                    ]),

                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make('Kalkulasi Kerusakan & Denda')
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\Toggle::make('damage_found')
                                    ->label('Kerusakan Ditemukan?')
                                    ->reactive(),

                                Forms\Components\Textarea::make('damage_description')
                                    ->label('Deskripsi Kerusakan')
                                    ->visible(fn (callable $get) => $get('damage_found'))
                                    ->rows(3),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('damage_cost')
                                            ->label('Biaya Kerusakan (Rp)')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->default(0),

                                        Forms\Components\TextInput::make('dirty_fine')
                                            ->label('Denda Mobil Kotor (Rp)')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->default(0),

                                        Forms\Components\TextInput::make('fuel_fine')
                                            ->label('Denda Kekurangan BBM (Rp)')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->default(0),
                                    ]),

                                Forms\Components\FileUpload::make('damage_photos')
                                    ->label('Foto Bukti Kerusakan')
                                    ->multiple()
                                    ->disk('public')
                                    ->directory('operational-damage-photos')
                                    ->image()
                                    ->visible(fn (callable $get) => $get('damage_found')),
                            ]),

                        Forms\Components\Section::make('Konfirmasi Pelanggan')
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\Toggle::make('customer_confirmed')
                                    ->label('Pelanggan Konfirmasi Setuju'),

                                Forms\Components\Textarea::make('customer_note')
                                    ->label('Catatan dari Pelanggan')
                                    ->rows(4),
                            ]),
                    ]),
            ]);
    }
}
