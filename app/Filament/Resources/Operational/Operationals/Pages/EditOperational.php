<?php

namespace App\Filament\Resources\Operational\Operationals\Pages;

use App\Filament\Resources\Operational\Operationals\OperationalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOperational extends EditRecord
{
    protected static string $resource = OperationalResource::class;

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
