<?php

namespace App\Filament\Resources\Drivers;

use App\Filament\Resources\Drivers\Pages\CreateDriver;
use App\Filament\Resources\Drivers\Pages\EditDriver;
use App\Filament\Resources\Drivers\Pages\ListDrivers;
use App\Filament\Resources\Drivers\Pages\ViewDriver;
use App\Filament\Resources\Drivers\Schemas\DriverForm;
use App\Filament\Resources\Drivers\Schemas\DriverInfolist;
use App\Filament\Resources\Drivers\Tables\DriversTable;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class DriverResource extends Resource
{
    protected static ?string $model = Driver::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'Data Driver';

    protected static ?string $pluralLabel = 'Data Driver';

    protected static ?string $modelLabel = 'Driver';

    protected static ?string $navigationGroup = 'Karyawan';

    protected static ?string $recordTitleAttribute = 'Driver';

    public static function canAccess(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user?->hasAnyRole(['super-admin', 'owner']) ?? false;
    }

    public static function form(Form $form): Form
    {
        return DriverForm::configure($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return DriverInfolist::configure($infolist);
    }

    public static function table(Table $table): Table
    {
        return DriversTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SchedulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDrivers::route('/'),
            'create' => CreateDriver::route('/create'),
            'view' => ViewDriver::route('/{record}'),
            'edit' => EditDriver::route('/{record}/edit'),
        ];
    }
}
