<?php

namespace App\Filament\Resources\Operational\LocationSurveys\Pages;

use App\Filament\Resources\Operational\LocationSurveys\LocationSurveyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLocationSurvey extends CreateRecord
{
    protected static string $resource = LocationSurveyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Flatten nested JSON fields dari Toggle ke array
        return $data;
    }
}
