<?php

namespace App\Filament\Resources\LocationSurveys;

use App\Filament\Resources\LocationSurveys\Pages\CreateLocationSurvey;
use App\Filament\Resources\LocationSurveys\Pages\EditLocationSurvey;
use App\Filament\Resources\LocationSurveys\Pages\ListLocationSurveys;
use App\Filament\Resources\LocationSurveys\Pages\ViewLocationSurvey;
use App\Filament\Resources\LocationSurveys\Schemas\LocationSurveyForm;
use App\Filament\Resources\LocationSurveys\Schemas\LocationSurveyInfolist;
use App\Filament\Resources\LocationSurveys\Tables\LocationSurveysTable;
use App\Models\LocationSurvey;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class LocationSurveyResource extends Resource
{
    protected static ?string $model = LocationSurvey::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $recordTitleAttribute = 'surveyor_name';

    protected static ?string $navigationGroup = 'Manajemen Operasional';

    protected static ?string $navigationLabel = 'Survei Lokasi';

    protected static ?string $pluralModelLabel = 'Survei Lokasi';

    public static function form(Form $form): Form
    {
        return LocationSurveyForm::configure($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return LocationSurveyInfolist::configure($infolist);
    }

    public static function table(Table $table): Table
    {
        return LocationSurveysTable::configure($table);
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
            'index' => ListLocationSurveys::route('/'),
            'create' => CreateLocationSurvey::route('/create'),
            'view' => ViewLocationSurvey::route('/{record}'),
            'edit' => EditLocationSurvey::route('/{record}/edit'),
        ];
    }
}
