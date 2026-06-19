<?php

namespace App\Filament\Resources\Operationals\Schemas;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\KeyValueEntry;

class OperationalInfolist
{
    public static function configure(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(3)
                    ->schema([
                        Group::make()
                            ->schema([
                                Section::make('Informasi Inspeksi & Armada')
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
                                                TextEntry::make('booking.booking_code')
                                                    ->label('Kode Booking')
                                                    ->fontFamily('mono')
                                                    ->weight('semibold')
                                                    ->icon('heroicon-m-calendar'),
                                                TextEntry::make('inspector_name')
                                                    ->label('Nama Inspektur')
                                                    ->icon('heroicon-m-user'),
                                                TextEntry::make('inspection_type')
                                                    ->label('Tipe Inspeksi')
                                                    ->badge()
                                                    ->color(fn (string $state): string => match ($state) {
                                                        'pre_rental' => 'info',
                                                        'post_rental' => 'success',
                                                        default => 'gray',
                                                    })
                                                    ->formatStateUsing(fn ($state) => $state === 'pre_rental' ? 'Sebelum Sewa' : 'Setelah Sewa'),
                                                TextEntry::make('inspected_at')
                                                    ->label('Waktu Inspeksi')
                                                    ->dateTime('d M Y H:i')
                                                    ->icon('heroicon-m-clock'),
                                            ]),
                                    ]),

                                Section::make('Kondisi Kelayakan Kendaraan')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('odometer_km')
                                                    ->label('Odometer')
                                                    ->numeric()
                                                    ->suffix(' KM')
                                                    ->icon('heroicon-m-chart-bar'),
                                                TextEntry::make('fuel_level')
                                                    ->label('Level BBM')
                                                    ->badge()
                                                    ->color(fn (string $state): string => match ($state) {
                                                        'full' => 'success',
                                                        'three_quarter' => 'info',
                                                        'half' => 'warning',
                                                        'quarter' => 'danger',
                                                        'empty' => 'danger',
                                                        default => 'gray',
                                                    })
                                                    ->formatStateUsing(fn ($state) => match ($state) {
                                                        'full' => 'Full',
                                                        'three_quarter' => '3/4',
                                                        'half' => '1/2',
                                                        'quarter' => '1/4',
                                                        'empty' => 'Kosong',
                                                        default => $state,
                                                    })
                                                    ->icon('heroicon-m-bolt'),
                                            ]),
                                        Grid::make(2)
                                            ->schema([
                                                KeyValueEntry::make('exterior')
                                                    ->label('Checklist Eksterior'),
                                                KeyValueEntry::make('interior')
                                                    ->label('Checklist Interior'),
                                                KeyValueEntry::make('equipment')
                                                    ->label('Peralatan & Surat'),
                                                KeyValueEntry::make('engine')
                                                    ->label('Checklist Mesin'),
                                            ]),
                                    ]),

                                Section::make('Dokumentasi Foto')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                ImageEntry::make('photos')
                                                    ->label('Foto Fisik Mobil')
                                                    ->disk('public')
                                                    ->size(120),
                                                ImageEntry::make('fuel_photos')
                                                    ->label('Foto Indikator BBM')
                                                    ->disk('public')
                                                    ->size(120),
                                            ]),
                                    ]),
                            ])
                            ->columnSpan(2),

                        Group::make()
                            ->schema([
                                Section::make('Status Persetujuan')
                                    ->schema([
                                        TextEntry::make('status')
                                            ->label('Status Inspeksi')
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'approved' => 'success',
                                                'pending' => 'warning',
                                                default => 'gray',
                                            })
                                            ->formatStateUsing(fn ($state) => ucfirst($state)),
                                        TextEntry::make('notes')
                                            ->label('Catatan Internal')
                                            ->placeholder('Tidak ada catatan.'),
                                    ]),

                                Section::make('Kerusakan & Denda')
                                    ->schema([
                                        IconEntry::make('damage_found')
                                            ->label('Kerusakan Ditemukan')
                                            ->boolean(),
                                        TextEntry::make('damage_description')
                                            ->label('Deskripsi Kerusakan')
                                            ->placeholder('Tidak ada kerusakan.'),
                                        TextEntry::make('damage_cost')
                                            ->label('Biaya Kerusakan')
                                            ->money('IDR')
                                            ->color('danger'),
                                        TextEntry::make('dirty_fine')
                                            ->label('Denda Mobil Kotor')
                                            ->money('IDR')
                                            ->color('danger'),
                                        TextEntry::make('fuel_fine')
                                            ->label('Denda Kurang BBM')
                                            ->money('IDR')
                                            ->color('danger'),
                                        ImageEntry::make('damage_photos')
                                            ->label('Foto Kerusakan')
                                            ->disk('public')
                                            ->size(100),
                                    ]),

                                Section::make('Persetujuan Pelanggan')
                                    ->schema([
                                        IconEntry::make('customer_confirmed')
                                            ->label('Konfirmasi Pelanggan')
                                            ->boolean(),
                                        TextEntry::make('customer_note')
                                            ->label('Catatan Pelanggan')
                                            ->placeholder('Tidak ada catatan.'),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
