<?php

namespace App\Filament\Resources\Operational\Operationals\Pages;

use App\Filament\Resources\Operational\Operationals\OperationalResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOperational extends CreateRecord
{
    protected static string $resource = OperationalResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
