<?php

namespace App\Filament\Resources\Operational\VehicleInspections\Pages;

use App\Filament\Resources\Operational\VehicleInspections\VehicleInspectionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVehicleInspection extends CreateRecord
{
    protected static string $resource = VehicleInspectionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
