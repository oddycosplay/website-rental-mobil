<?php

namespace App\Filament\Resources\LocationSurveys\Pages;

use App\Filament\Resources\LocationSurveys\LocationSurveyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLocationSurveys extends ListRecords
{
    protected static string $resource = LocationSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
