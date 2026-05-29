<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists;

class CustomerInfolist
{
    public static function configure(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Akun')
                    ->description('Rincian akun pengguna terdaftar.')
                    ->icon('heroicon-m-user-circle')
                    ->collapsible()
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Nama Lengkap')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('email')
                                    ->label('Alamat Email')
                                    ->copyable(),
                                Infolists\Components\TextEntry::make('phone')
                                    ->label('Nomor Telepon')
                                    ->copyable(),
                            ]),
                    ]),

                Infolists\Components\Section::make('Profil & Identitas Pelanggan')
                    ->description('Rincian identitas resmi penyewa berdasarkan kartu identitas.')
                    ->icon('heroicon-m-identification')
                    ->collapsible()
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('customer.nik')
                                    ->label('NIK KTP')
                                    ->copyable()
                                    ->placeholder('Belum ditentukan pada requirement.'),
                                Infolists\Components\TextEntry::make('customer.sim_number')
                                    ->label('Nomor SIM')
                                    ->copyable()
                                    ->placeholder('Belum ditentukan pada requirement.'),
                                Infolists\Components\TextEntry::make('customer.no_kk')
                                    ->label('Nomor Kartu Keluarga')
                                    ->copyable()
                                    ->placeholder('Belum ditentukan pada requirement.'),
                                Infolists\Components\TextEntry::make('customer.nip_nim')
                                    ->label('No. ID Card / NIP / NIM')
                                    ->copyable()
                                    ->placeholder('Belum ditentukan pada requirement.'),
                                Infolists\Components\TextEntry::make('customer.pekerjaan')
                                    ->label('Status Pekerjaan')
                                    ->placeholder('Belum ditentukan pada requirement.'),
                                Infolists\Components\TextEntry::make('customer.customer_status')
                                    ->label('Status Verifikasi')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        default => 'gray',
                                    }),
                            ]),
                        Infolists\Components\TextEntry::make('customer.address')
                            ->label('Alamat Sesuai KTP')
                            ->placeholder('Belum ditentukan pada requirement.'),
                    ]),

                Infolists\Components\Section::make('Berkas & Dokumen Verifikasi')
                    ->description('Foto fisik dokumen resmi renter untuk verifikasi.')
                    ->icon('heroicon-m-document-text')
                    ->collapsible()
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\ImageEntry::make('customer.selfie_image')
                                    ->label('Foto Selfie Renter')
                                    ->disk('public')
                                    ->size(200)
                                    ->placeholder('Tidak ada berkas.'),
                                Infolists\Components\ImageEntry::make('customer.ktp_image')
                                    ->label('Foto KTP Resmi')
                                    ->disk('public')
                                    ->size(200)
                                    ->placeholder('Tidak ada berkas.'),
                                Infolists\Components\ImageEntry::make('customer.sim_image')
                                    ->label('Foto SIM Resmi')
                                    ->disk('public')
                                    ->size(200)
                                    ->placeholder('Tidak ada berkas.'),
                                Infolists\Components\ImageEntry::make('customer.kk_photo')
                                    ->label('Foto Kartu Keluarga')
                                    ->disk('public')
                                    ->size(200)
                                    ->placeholder('Tidak ada berkas.'),
                                Infolists\Components\ImageEntry::make('customer.id_card_photo')
                                    ->label('Foto ID Card Pekerjaan / Pelajar')
                                    ->disk('public')
                                    ->size(200)
                                    ->placeholder('Tidak ada berkas.')
                                    ->columnSpan(2),
                            ]),
                    ]),
            ]);
    }
}
