<?php

namespace App\Filament\Resources\Operational\LocationSurveys;

use App\Models\LocationSurvey;
use App\Filament\Resources\Operational\LocationSurveys\Schemas\LocationSurveyForm;
use App\Filament\Resources\Operational\LocationSurveys\Tables\LocationSurveyTable;
use App\Filament\Resources\Operational\LocationSurveys\Pages\ListLocationSurveys;
use App\Filament\Resources\Operational\LocationSurveys\Pages\CreateLocationSurvey;
use App\Filament\Resources\Operational\LocationSurveys\Pages\EditLocationSurvey;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class LocationSurveyResource extends Resource
{
    protected static ?string $model = LocationSurvey::class;

    protected static ?string $navigationIcon   = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel  = 'Survey Lokasi';
    protected static ?string $navigationGroup  = 'Operasional';
    protected static ?int    $navigationSort   = 1;
    protected static ?string $modelLabel       = 'Survey Lokasi';
    protected static ?string $pluralModelLabel = 'Survey Lokasi';
    protected static ?string $slug            = 'operational/location-surveys';

    public static function form(Form $form): Form
    {
        return LocationSurveyForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return LocationSurveyTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLocationSurveys::route('/'),
            'create' => CreateLocationSurvey::route('/create'),
            'edit'   => EditLocationSurvey::route('/{record}/edit'),
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
