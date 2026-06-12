<?php

namespace App\Filament\Resources\Operational\Operationals;

use App\Models\Operational;
use App\Filament\Resources\Operational\Operationals\Schemas\OperationalForm;
use App\Filament\Resources\Operational\Operationals\Tables\OperationalTable;
use App\Filament\Resources\Operational\Operationals\Pages\ListOperationals;
use App\Filament\Resources\Operational\Operationals\Pages\CreateOperational;
use App\Filament\Resources\Operational\Operationals\Pages\EditOperational;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class OperationalResource extends Resource
{
    protected static ?string $model = Operational::class;

    protected static ?string $navigationIcon   = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel  = 'Operasional';
    protected static ?string $navigationGroup  = 'Operasional';
    protected static ?int    $navigationSort   = 2;
    protected static ?string $modelLabel       = 'Operasional';
    protected static ?string $pluralModelLabel = 'Operasional';
    protected static ?string $slug            = 'operational/operationals';

    public static function form(Form $form): Form
    {
        return OperationalForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return OperationalTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListOperationals::route('/'),
            'create' => CreateOperational::route('/create'),
            'edit'   => EditOperational::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()->where('status', 'submitted')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
