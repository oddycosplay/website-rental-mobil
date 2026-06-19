<?php

namespace App\Filament\Resources\Operationals;

use App\Filament\Resources\Operationals\Pages\CreateOperational;
use App\Filament\Resources\Operationals\Pages\EditOperational;
use App\Filament\Resources\Operationals\Pages\ListOperationals;
use App\Filament\Resources\Operationals\Pages\ViewOperational;
use App\Filament\Resources\Operationals\Schemas\OperationalForm;
use App\Filament\Resources\Operationals\Schemas\OperationalInfolist;
use App\Filament\Resources\Operationals\Tables\OperationalsTable;
use App\Models\Operational;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class OperationalResource extends Resource
{
    protected static ?string $model = Operational::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $recordTitleAttribute = 'inspector_name';

    protected static ?string $navigationGroup = 'Manajemen Operasional';

    protected static ?string $navigationLabel = 'Inspeksi & Operasional';

    protected static ?string $pluralModelLabel = 'Inspeksi & Operasional';

    public static function form(Form $form): Form
    {
        return OperationalForm::configure($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return OperationalInfolist::configure($infolist);
    }

    public static function table(Table $table): Table
    {
        return OperationalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOperationals::route('/'),
            'create' => CreateOperational::route('/create'),
            'view' => ViewOperational::route('/{record}'),
            'edit' => EditOperational::route('/{record}/edit'),
        ];
    }
}
