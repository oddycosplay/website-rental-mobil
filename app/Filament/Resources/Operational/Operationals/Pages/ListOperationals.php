<?php

namespace App\Filament\Resources\Operational\Operationals\Pages;

use App\Filament\Resources\Operational\Operationals\OperationalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOperationals extends ListRecords
{
    protected static string $resource = OperationalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Operasional'),
        ];
    }
}
