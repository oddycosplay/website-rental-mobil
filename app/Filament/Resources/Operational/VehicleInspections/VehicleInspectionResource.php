<?php

namespace App\Filament\Resources\Operational\VehicleInspections;

use App\Models\VehicleInspection;
use App\Filament\Resources\Operational\VehicleInspections\Schemas\VehicleInspectionForm;
use App\Filament\Resources\Operational\VehicleInspections\Tables\VehicleInspectionTable;
use App\Filament\Resources\Operational\VehicleInspections\Pages\ListVehicleInspections;
use App\Filament\Resources\Operational\VehicleInspections\Pages\CreateVehicleInspection;
use App\Filament\Resources\Operational\VehicleInspections\Pages\EditVehicleInspection;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class VehicleInspectionResource extends Resource
{
    protected static ?string $model = VehicleInspection::class;

    protected static ?string $navigationIcon   = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel  = 'Pengecekan Kendaraan';
    protected static ?string $navigationGroup  = 'Operasional';
    protected static ?int    $navigationSort   = 2;
    protected static ?string $modelLabel       = 'Pengecekan Kendaraan';
    protected static ?string $pluralModelLabel = 'Pengecekan Kendaraan';
    protected static ?string $slug            = 'operational/vehicle-inspections';

    public static function form(Form $form): Form
    {
        return VehicleInspectionForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return VehicleInspectionTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListVehicleInspections::route('/'),
            'create' => CreateVehicleInspection::route('/create'),
            'edit'   => EditVehicleInspection::route('/{record}/edit'),
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
