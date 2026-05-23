<?php

namespace App\Filament\Resources\Cars\Schemas;

use App\Models\Car;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;
use Filament\Forms\Get;

use App\Models\Store;
use Illuminate\Support\Str;

class CarForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make('Informasi Kendaraan')
                            ->schema([
                                Forms\Components\TextInput::make('car_name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(Car::class, 'slug', ignoreRecord: true)
                                    ->disabled()
                                    ->dehydrated(),

                                Forms\Components\Hidden::make('brand_slug')
                                    ->required()
                                    ->dehydrated(),
                                
                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('brand_name')
                                            ->label('Merek Mobil (Brand)')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('brand_slug', Str::slug($state))),
                                        
                                        Forms\Components\TextInput::make('type_name')
                                            ->label('Tipe Mobil (Type)')
                                            ->required()
                                            ->maxLength(255),
                                    ]),

                                Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('plate_number')
                                            ->required()
                                            ->unique(Car::class, 'plate_number', ignoreRecord: true)
                                            ->label('No. Polisi'),
                                        
                                        Forms\Components\TextInput::make('year')
                                            ->required()
                                            ->numeric(),
                                        
                                        Forms\Components\TextInput::make('color')
                                            ->maxLength(50),
                                    ]),

                                Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('seat')
                                            ->required()
                                            ->numeric()
                                            ->default(4),
                                        
                                        Forms\Components\Select::make('transmission')
                                            ->options([
                                                'Manual' => 'Manual',
                                                'Automatic' => 'Automatic',
                                            ])
                                            ->required(),
                                        
                                        Forms\Components\Select::make('fuel_type')
                                            ->options([
                                                'Bensin' => 'Bensin',
                                                'Diesel' => 'Diesel',
                                                'Listrik' => 'Listrik',
                                            ])
                                            ->required(),
                                    ]),
                            ])->columnSpan(2),

                        Group::make()
                            ->schema([
                                Section::make('Status & Lokasi')
                                    ->schema([
                                        Forms\Components\Select::make('store_id')
                                            ->label('Toko/Cabang')
                                            ->relationship('store', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload(),

                                        Forms\Components\Select::make('status')
                                            ->options([
                                                'available' => 'Tersedia',
                                                'rented' => 'Sedang Disewa',
                                                'maintenance' => 'Perawatan',
                                            ])
                                            ->required()
                                            ->native(false)
                                            ->default('available'),

                                        Forms\Components\Toggle::make('is_available')
                                            ->label('Tampilkan di Website')
                                            ->default(true),

                                        Forms\Components\Toggle::make('featured')
                                            ->label('Mobil Unggulan')
                                            ->default(false),
                                    ]),

                                Section::make('Pricing')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_call_for_price')
                                            ->label('Call for Price (Sesuai Pesanan)')
                                            ->live(),

                                        Forms\Components\TextInput::make('daily_price')
                                            ->required()
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->hidden(fn (Get $get) => $get('is_call_for_price')),
                                        
                                        Forms\Components\TextInput::make('driver_daily_price')
                                            ->label('Biaya Driver Per Hari')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->hidden(fn (Get $get) => $get('is_call_for_price')),

                                        Forms\Components\TextInput::make('monthly_price')
                                            ->required()
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->hidden(fn (Get $get) => $get('is_call_for_price')),

                                        Forms\Components\TextInput::make('late_fee')
                                            ->required()
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->hidden(fn (Get $get) => $get('is_call_for_price')),
                                    ]),
                            ])->columnSpan(1),
                        
                        Section::make('Media & Galeri')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\FileUpload::make('thumbnail')
                                            ->label('Foto Utama (Thumbnail)')
                                            ->image()
                                            ->imageEditor()
                                            ->directory('cars')
                                            ->required(),
                                        
                                        Forms\Components\FileUpload::make('images')
                                            ->label('Foto Galeri Tambahan')
                                            ->multiple()
                                            ->directory('cars/gallery')
                                            ->reorderable()
                                            ->appendFiles(),
                                    ]),
                                
                                Forms\Components\RichEditor::make('description')
                                    ->label('Deskripsi Kendaraan')
                                    ->columnSpanFull(),
                            ])->columnSpanFull(),

                        Section::make('Sistem Telemetri GPS & IoT')
                            ->schema([
                                Grid::make(4)
                                    ->schema([
                                        Forms\Components\TextInput::make('latitude')
                                            ->numeric()
                                            ->label('Latitude GPS'),
                                        Forms\Components\TextInput::make('longitude')
                                            ->numeric()
                                            ->label('Longitude GPS'),
                                        Forms\Components\TextInput::make('speed')
                                            ->numeric()
                                            ->label('Kecepatan Terakhir (km/h)'),
                                        Forms\Components\TextInput::make('location_address')
                                            ->maxLength(255)
                                            ->label('Alamat GPS Terakhir'),
                                    ]),
                            ])->columnSpanFull(),

                        Section::make('Riwayat Perawatan & Inspeksi Berkala')
                            ->schema([
                                Forms\Components\Repeater::make('maintenances')
                                    ->label('Riwayat Pemeliharaan (Maintenance)')
                                    ->schema([
                                        Forms\Components\DatePicker::make('maintenance_date')
                                            ->required()
                                            ->label('Tanggal Servis'),
                                        Forms\Components\TextInput::make('cost')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->required()
                                            ->label('Biaya Perawatan'),
                                        Forms\Components\TextInput::make('description')
                                            ->required()
                                            ->label('Deskripsi Tindakan & Suku Cadang'),
                                    ])
                                    ->columns(3)
                                    ->default([])
                                    ->reorderable()
                                    ->collapsible(),
                                
                                Forms\Components\Repeater::make('inspections')
                                    ->label('Checklist Inspeksi Berkala')
                                    ->schema([
                                        Forms\Components\DatePicker::make('inspection_date')
                                            ->required()
                                            ->label('Tanggal Inspeksi'),
                                        Forms\Components\TextInput::make('inspector')
                                            ->required()
                                            ->label('Nama Inspektor'),
                                        Forms\Components\Select::make('condition')
                                            ->options([
                                                'excellent' => 'Sangat Baik',
                                                'good' => 'Baik',
                                                'fair' => 'Cukup',
                                                'poor' => 'Butuh Servis',
                                            ])
                                            ->required()
                                            ->label('Kondisi Unit'),
                                        Forms\Components\Toggle::make('is_clean')
                                            ->label('Kebersihan Kabin Steril')
                                            ->default(true),
                                    ])
                                    ->columns(4)
                                    ->default([])
                                    ->reorderable()
                                    ->collapsible(),
                            ])->columnSpanFull(),
                    ]),
            ]);
    }
}
