<?php

namespace App\Filament\Resources\Operational\LocationSurveys\Pages;

use App\Filament\Resources\Operational\LocationSurveys\LocationSurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLocationSurveys extends ListRecords
{
    protected static string $resource = LocationSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Survey'),
        ];
    }
}
