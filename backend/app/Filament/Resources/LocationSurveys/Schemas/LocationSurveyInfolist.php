<?php

namespace App\Filament\Resources\LocationSurveys\Schemas;

use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Group;

class LocationSurveyInfolist
{
    public static function configure(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(3)
                    ->schema([
                        Group::make()
                            ->schema([
                                Section::make('Informasi Survei & Booking')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('booking.booking_code')
                                                    ->label('Kode Booking')
                                                    ->fontFamily('mono')
                                                    ->weight('semibold')
                                                    ->icon('heroicon-m-calendar'),

                                                TextEntry::make('booking.customer.name')
                                                    ->label('Nama Customer')
                                                    ->icon('heroicon-m-user'),

                                                TextEntry::make('surveyor_name')
                                                    ->label('Nama Surveyor')
                                                    ->icon('heroicon-m-user-circle'),

                                                TextEntry::make('survey_date')
                                                    ->label('Tanggal Survei')
                                                    ->date('d M Y')
                                                    ->icon('heroicon-m-clock'),

                                                TextEntry::make('survey_type')
                                                    ->label('Tipe Survei')
                                                    ->badge()
                                                    ->color(fn (string $state): string => match ($state) {
                                                        'delivery' => 'info',
                                                        'pickup' => 'success',
                                                        default => 'gray',
                                                    })
                                                    ->formatStateUsing(fn ($state) => $state === 'delivery' ? 'Delivery' : 'Pickup'),
                                            ]),

                                        TextEntry::make('address')
                                            ->label('Alamat Rumah Kustomer')
                                            ->icon('heroicon-m-map-pin'),
                                    ]),

                                Section::make('Parameter Kelayakan Lokasi')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Group::make()
                                                    ->schema([
                                                        TextEntry::make('residence_status.status')
                                                            ->label('Status Tempat Tinggal')
                                                            ->weight('bold'),
                                                        TextEntry::make('residence_status.proof')
                                                            ->label('Bukti Kepemilikan'),
                                                    ])
                                                    ->columnSpan(1),

                                                Group::make()
                                                    ->schema([
                                                        TextEntry::make('job_status.occupation')
                                                            ->label('Pekerjaan Customer')
                                                            ->weight('bold'),
                                                        TextEntry::make('job_status.company')
                                                            ->label('Nama Instansi / Perusahaan'),
                                                    ])
                                                    ->columnSpan(1),

                                                Group::make()
                                                    ->schema([
                                                        TextEntry::make('neighbor_interview.rt_rw_verification')
                                                            ->label('Verifikasi RT/RW / Tetangga')
                                                            ->weight('bold'),
                                                        TextEntry::make('neighbor_interview.character')
                                                            ->label('Karakter Kustomer'),
                                                    ])
                                                    ->columnSpan(1),
                                            ]),
                                    ]),

                                Section::make('Dokumentasi Foto Lokasi')
                                    ->schema([
                                        ImageEntry::make('photos')
                                            ->label('Foto Fisik Rumah')
                                            ->disk('public')
                                            ->size(150),
                                    ]),
                            ])
                            ->columnSpan(2),

                        Group::make()
                            ->schema([
                                Section::make('Rekomendasi & Verifikasi')
                                    ->schema([
                                        TextEntry::make('recommendation')
                                            ->label('Rekomendasi')
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'layak' => 'success',
                                                'tidak_layak' => 'danger',
                                                default => 'gray',
                                            })
                                            ->formatStateUsing(fn ($state) => match ($state) {
                                                'layak' => 'LAYAK DISEWAKAN',
                                                'tidak_layak' => 'TIDAK LAYAK DISEWAKAN',
                                                default => strtoupper($state),
                                            }),

                                        TextEntry::make('status')
                                            ->label('Status Persetujuan')
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'approved' => 'success',
                                                'pending' => 'warning',
                                                'rejected' => 'danger',
                                                default => 'gray',
                                            })
                                            ->formatStateUsing(fn ($state) => match ($state) {
                                                'approved' => 'Disetujui',
                                                'pending' => 'Pending',
                                                'rejected' => 'Ditolak',
                                                default => ucfirst($state),
                                            }),

                                        TextEntry::make('approvedBy.name')
                                            ->label('Disetujui Oleh')
                                            ->placeholder('Belum disetujui'),

                                        TextEntry::make('approved_at')
                                            ->label('Waktu Persetujuan')
                                            ->dateTime('d M Y H:i')
                                            ->placeholder('-'),

                                        TextEntry::make('notes')
                                            ->label('Catatan Survei')
                                            ->placeholder('Tidak ada catatan.'),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
