<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DashboardHeader extends Widget
{
    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';

    protected static string $view = 'filament.widgets.dashboard-header';

    public function getHeaderData(): array
    {
        return [
            'tenant' => \Filament\Facades\Filament::getTenant(),
        ];
    }
}
