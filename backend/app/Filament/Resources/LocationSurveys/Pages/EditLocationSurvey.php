<?php

namespace App\Filament\Resources\LocationSurveys\Pages;

use App\Filament\Resources\LocationSurveys\LocationSurveyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLocationSurvey extends EditRecord
{
    protected static string $resource = LocationSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
