<?php

namespace App\Filament\Resources\Operationals\Pages;

use App\Filament\Resources\Operationals\OperationalResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOperational extends ViewRecord
{
    protected static string $resource = OperationalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
