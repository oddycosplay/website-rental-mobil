<?php

namespace App\Filament\Resources\Drivers\Schemas;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists;

class DriverInfolist
{
    public static function configure(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Driver')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Nama Lengkap'),
                                Infolists\Components\TextEntry::make('phone')
                                    ->label('Nomor Telepon'),
                                Infolists\Components\TextEntry::make('license_number')
                                    ->label('Nomor SIM'),
                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'available' => 'success',
                                        'busy' => 'warning',
                                        'off' => 'danger',
                                        default => 'gray',
                                    }),
                            ]),
                    ]),
                
                Section::make('Foto Driver')
                    ->schema([
                        Infolists\Components\ImageEntry::make('photo')
                            ->circular()
                            ->label(false),
                    ]),
            ]);
    }
}
