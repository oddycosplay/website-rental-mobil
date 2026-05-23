<?php

namespace App\Filament\Resources\Drivers\Schemas;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms;

class DriverForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Driver')
                    ->description('Detail identitas dan status operasional pengemudi.')
                    ->icon('heroicon-m-identification')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-m-user'),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(20)
                                    ->prefixIcon('heroicon-m-phone'),
                                Forms\Components\TextInput::make('license_number')
                                    ->label('Nomor SIM')
                                    ->required()
                                    ->maxLength(50)
                                    ->prefixIcon('heroicon-m-credit-card'),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'available' => 'Tersedia',
                                        'busy' => 'Sedang Bertugas',
                                        'off' => 'Libur',
                                    ])
                                    ->default('available')
                                    ->required()
                                    ->native(false)
                                    ->prefixIcon('heroicon-m-check-circle'),
                            ]),
                    ]),
                
                Section::make('Foto & Dokumen')
                    ->description('Lampirkan foto pengemudi untuk identifikasi.')
                    ->icon('heroicon-m-camera')
                    ->schema([
                        Forms\Components\FileUpload::make('photo')
                            ->image()
                            ->imageEditor()
                            ->directory('drivers/photos')
                            ->label('Foto Driver')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
