<?php

namespace App\Filament\Resources\Operational\LocationSurveys\Pages;

use App\Filament\Resources\Operational\LocationSurveys\LocationSurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLocationSurvey extends EditRecord
{
    protected static string $resource = LocationSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
