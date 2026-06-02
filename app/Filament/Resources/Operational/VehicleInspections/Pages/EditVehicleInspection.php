<?php

namespace App\Filament\Resources\Operational\VehicleInspections\Pages;

use App\Filament\Resources\Operational\VehicleInspections\VehicleInspectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVehicleInspection extends EditRecord
{
    protected static string $resource = VehicleInspectionResource::class;

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
