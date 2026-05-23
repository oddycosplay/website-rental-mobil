<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Infolists;

class UserInfolist
{
    public static function configure(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('User Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name'),
                        Infolists\Components\TextEntry::make('email'),
                        Infolists\Components\TextEntry::make('role')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'admin' => 'danger',
                                'staff' => 'warning',
                                'customer' => 'info',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('branch.name')
                            ->label('Cabang')
                            ->placeholder('Global / Semua Cabang'),
                    ]),
            ]);
    }
}
