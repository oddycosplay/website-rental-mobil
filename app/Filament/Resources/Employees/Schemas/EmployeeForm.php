<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Profil Karyawan')
                    ->description('Informasi profil utama karyawan Siliwangi Rental.')
                    ->icon('heroicon-m-identification')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nip')
                                    ->label('NIP / Nomor Induk Pegawai')
                                    ->placeholder('NIP-00000')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(50)
                                    ->prefixIcon('heroicon-m-qr-code'),
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->label('Nama Lengkap')
                                    ->placeholder('Nama Lengkap Karyawan')
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-m-user'),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->label('Alamat Email')
                                    ->placeholder('karyawan@siliwangirental.com')
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->prefixIcon('heroicon-m-envelope'),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->label('Nomor Telepon')
                                    ->placeholder('08xxxxxxxxxx')
                                    ->maxLength(20)
                                    ->prefixIcon('heroicon-m-phone'),
                                Forms\Components\Select::make('position')
                                    ->label('Jabatan')
                                    ->options([
                                        'Super Admin' => 'Super Admin',
                                        'Owner' => 'Owner',
                                        'Finance' => 'Finance',
                                        'Driver' => 'Driver',
                                        'Surveyor' => 'Surveyor / Lapangan',
                                        'Operational' => 'Operational Staff',
                                    ])
                                    ->required()
                                    ->native(false)
                                    ->prefixIcon('heroicon-m-briefcase'),
                                Forms\Components\Select::make('store_id')
                                    ->label('Cabang (Store)')
                                    ->relationship('store', 'name')
                                    ->placeholder('Pilih Cabang Tugas Karyawan')
                                    ->preload()
                                    ->searchable()
                                    ->native(false)
                                    ->prefixIcon('heroicon-m-building-storefront'),
                            ]),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark'),
                    ]),

                Section::make('Akun Akses Dashboard')
                    ->description('Mengatur akun login dan peran (roles) karyawan untuk mengakses panel admin.')
                    ->icon('heroicon-m-lock-closed')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Toggle::make('has_account')
                            ->label('Aktifkan Akses Login Dashboard')
                            ->live()
                            ->dehydrated(false)
                            ->afterStateHydrated(function ($state, $set, $record) {
                                if ($record && $record->user_id) {
                                    $set('has_account', true);
                                    $set('user_roles', $record->user->roles->pluck('id')->toArray());
                                }
                            }),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->label('Password Baru')
                                    ->placeholder('Minimal 8 karakter')
                                    ->dehydrated(false)
                                    ->visible(fn ($get) => $get('has_account'))
                                    ->required(fn ($record, $get) => !$record && $get('has_account'))
                                    ->maxLength(255),
                                
                                Forms\Components\Select::make('user_roles')
                                    ->label('Role Akses')
                                    ->placeholder('Pilih Hak Akses Karyawan')
                                    ->relationship('user.roles', 'name', modifyQueryUsing: fn ($query) => $query->where('name', '!=', 'customer'))
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->native(false)
                                    ->visible(fn ($get) => $get('has_account'))
                                    ->required(fn ($get) => $get('has_account'))
                                    ->dehydrated(false),
                            ]),
                    ]),
            ]);
    }
}
