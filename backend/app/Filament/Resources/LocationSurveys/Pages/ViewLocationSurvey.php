<?php

namespace App\Filament\Resources\LocationSurveys\Pages;

use App\Filament\Resources\LocationSurveys\LocationSurveyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLocationSurvey extends ViewRecord
{
    protected static string $resource = LocationSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
