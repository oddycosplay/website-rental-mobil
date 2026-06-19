<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms;
use Illuminate\Support\Str;

class StoreForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Toko / Cabang')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Cabang')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(table: 'stores', column: 'slug', ignoreRecord: true),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->maxLength(20),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email Cabang')
                                    ->email()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat')
                            ->required()
                            ->rows(3),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('city')
                                    ->label('Kota')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('province')
                                    ->label('Provinsi')
                                    ->maxLength(100),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('google_maps')
                                    ->label('Koordinat Map (Latitude, Longitude)')
                                    ->placeholder('-6.921876, 107.611116')
                                    ->helperText('Masukkan koordinat lokasi cabang (contoh: -6.921876, 107.611116).')
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('status')
                                    ->label('Status Aktif')
                                    ->inline(false)
                                    ->default(true)
                                    ->required(),
                            ]),
                    ]),
            ]);
    }
}
