<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms;

class StoreForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Toko / Cabang')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(20),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Textarea::make('address')
                                    ->required()
                                    ->rows(3),
                                Forms\Components\TextInput::make('city')
                                    ->maxLength(100),
                            ]),
                    ]),
            ]);
    }
}
