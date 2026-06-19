<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Kontak Pelanggan')
                    ->description('Data nama dan kontak utama pelanggan.')
                    ->icon('heroicon-m-user-circle')
                    ->collapsible()
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nama Lengkap')
                                    ->prefixIcon('heroicon-m-user'),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->maxLength(255)
                                    ->label('Alamat Email')
                                    ->unique(ignoreRecord: true)
                                    ->prefixIcon('heroicon-m-envelope'),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(20)
                                    ->label('Nomor Telepon')
                                    ->prefixIcon('heroicon-m-phone'),
                            ]),
                    ]),

                Section::make('Profil & Identitas Pelanggan')
                    ->description('Informasi lengkap identitas resmi penyewa.')
                    ->icon('heroicon-m-identification')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nik')
                                    ->maxLength(50)
                                    ->label('NIK KTP')
                                    ->prefixIcon('heroicon-m-identification')
                                    ->placeholder('32xxxxxxxxxxxxxx'),
                                Forms\Components\TextInput::make('sim_number')
                                    ->maxLength(50)
                                    ->label('No. SIM')
                                    ->prefixIcon('heroicon-m-credit-card')
                                    ->placeholder('xxxxxxxxxxxx'),
                                Forms\Components\TextInput::make('no_kk')
                                    ->maxLength(50)
                                    ->label('No. Kartu Keluarga')
                                    ->prefixIcon('heroicon-m-home')
                                    ->placeholder('32xxxxxxxxxxxxxx'),
                                Forms\Components\TextInput::make('nip_nim')
                                    ->maxLength(50)
                                    ->label('No. ID Card / NIP / NIM')
                                    ->prefixIcon('heroicon-m-academic-cap')
                                    ->placeholder('xxxxxxxxxxxx'),
                                Forms\Components\TextInput::make('pekerjaan')
                                    ->maxLength(100)
                                    ->label('Status Pekerjaan')
                                    ->prefixIcon('heroicon-m-briefcase')
                                    ->placeholder('PNS, Swasta, Mahasiswa, dll.'),
                                Forms\Components\Select::make('customer_status')
                                    ->label('Status Verifikasi')
                                    ->options([
                                        'pending' => 'Pending / Menunggu Verifikasi',
                                        'approved' => 'Approved / Terverifikasi',
                                        'rejected' => 'Rejected / Ditolak',
                                    ])
                                    ->default('pending')
                                    ->required()
                                    ->native(false),
                            ]),
                        Forms\Components\Textarea::make('address')
                            ->rows(3)
                            ->label('Alamat Rumah Sesuai KTP')
                            ->placeholder('Alamat lengkap sesuai KTP...'),
                    ]),

                Section::make('Dokumen Verifikasi')
                    ->description('Berkas digital identitas resmi dan foto selfie pelanggan.')
                    ->icon('heroicon-m-document-text')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\FileUpload::make('selfie_image')
                                    ->image()
                                    ->imageEditor()
                                    ->maxSize(2048)
                                    ->directory('customers/selfies')
                                    ->label('Foto Selfie Renter')
                                    ->columnSpan(1),
                                Forms\Components\FileUpload::make('ktp_image')
                                    ->image()
                                    ->imageEditor()
                                    ->maxSize(2048)
                                    ->directory('customers/identities')
                                    ->label('Foto KTP Resmi')
                                    ->columnSpan(1),
                                Forms\Components\FileUpload::make('sim_image')
                                    ->image()
                                    ->imageEditor()
                                    ->maxSize(2048)
                                    ->directory('customers/sims')
                                    ->label('Foto SIM Resmi')
                                    ->columnSpan(1),
                                Forms\Components\FileUpload::make('kk_image')
                                    ->image()
                                    ->imageEditor()
                                    ->maxSize(2048)
                                    ->directory('customers/kk')
                                    ->label('Foto Kartu Keluarga')
                                    ->columnSpan(1),
                                Forms\Components\FileUpload::make('pelajar_image')
                                    ->image()
                                    ->imageEditor()
                                    ->maxSize(2048)
                                    ->directory('customers/idcards')
                                    ->label('Foto ID Card Pekerjaan / Pelajar')
                                    ->columnSpan(2),
                            ]),
                    ]),

                Section::make('Akun Login Pelanggan')
                    ->description('Mengatur password untuk akses login pelanggan.')
                    ->icon('heroicon-m-lock-closed')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->label('Password')
                                    ->placeholder(fn ($record) => $record ? 'Kosongkan jika tidak ingin mengubah' : 'Minimal 8 karakter')
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->required(fn ($record) => !$record)
                                    ->maxLength(255),
                            ]),
                    ]),
            ]);
    }
}
