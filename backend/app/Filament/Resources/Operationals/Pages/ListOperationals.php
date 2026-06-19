<?php

namespace App\Filament\Resources\Operationals\Pages;

use App\Filament\Resources\Operationals\OperationalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOperationals extends ListRecords
{
    protected static string $resource = OperationalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
