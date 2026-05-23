<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms;

class CustomerForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pelanggan')
                    ->description('Data diri dan kontak utama pelanggan.')
                    ->icon('heroicon-m-user-circle')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-m-user'),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->maxLength(255)
                                    ->prefixIcon('heroicon-m-envelope'),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(20)
                                    ->prefixIcon('heroicon-m-phone'),
                                Forms\Components\TextInput::make('identity_number')
                                    ->maxLength(50)
                                    ->label('NIK / No. Identitas')
                                    ->prefixIcon('heroicon-m-identification'),
                            ]),
                        Forms\Components\Textarea::make('address')
                            ->rows(3)
                            ->placeholder('Alamat lengkap tempat tinggal...'),
                    ]),

                Section::make('Dokumen Identitas')
                    ->description('Lampirkan foto identitas resmi (KTP/Passport).')
                    ->icon('heroicon-m-document-text')
                    ->schema([
                        Forms\Components\FileUpload::make('identity_photo')
                            ->image()
                            ->imageEditor()
                            ->directory('customers/identities')
                            ->label('Foto KTP / Identitas')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
