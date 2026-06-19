<?php

namespace App\Filament\Resources\Operationals\Pages;

use App\Filament\Resources\Operationals\OperationalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOperational extends EditRecord
{
    protected static string $resource = OperationalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
