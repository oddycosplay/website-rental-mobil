<?php

namespace App\Filament\Pages;

use App\Models\Car;
use App\Models\Booking;
use Filament\Pages\Page;

class CarSchedule extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected static string $view = 'filament.pages.car-schedule';

    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Operasional';

    protected static ?string $navigationLabel = 'Kalender Armada';

    protected static ?string $title = 'Kalender Armada';

    public $cars;

    public function mount()
    {
        $this->cars = Car::with(['bookings' => function($query) {
            $query->where('pickup_date', '>=', now()->startOfMonth())
                  ->where('return_date', '<=', now()->endOfMonth()->addMonths(2))
                  ->where('booking_status', '!=', 'cancelled');
        }])->get();
    }
}
