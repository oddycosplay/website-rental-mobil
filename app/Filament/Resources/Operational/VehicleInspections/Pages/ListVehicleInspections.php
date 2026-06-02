<?php

namespace App\Filament\Resources\Operational\VehicleInspections\Pages;

use App\Filament\Resources\Operational\VehicleInspections\VehicleInspectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicleInspections extends ListRecords
{
    protected static string $resource = VehicleInspectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Pengecekan'),
        ];
    }
}
