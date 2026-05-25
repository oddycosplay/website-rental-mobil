<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;

class CarScheduleController extends Controller
{
    public function index()
    {
        // Get all active cars
        $cars = Car::with(['branch'])->get();
        
        // Get upcoming and ongoing bookings
        $bookings = Booking::with(['customer', 'car'])
            ->whereIn('booking_status', ['pending', 'confirmed', 'ongoing'])
            ->orderBy('pickup_date')
            ->get();
            
        // Extract active/scheduled maintenance records from the cars collection
        $maintenances = collect();
        foreach ($cars as $car) {
            $records = $car->maintenances;
            if (is_array($records)) {
                foreach ($records as $m) {
                    $mDate = $m['maintenance_date'] ?? null;
                    if ($mDate) {
                        $dateCarbon = \Carbon\Carbon::parse($mDate);
                        
                        // Determine status
                        $status = 'completed';
                        if ($dateCarbon->isFuture()) {
                            $status = 'scheduled';
                        } elseif ($car->status === 'maintenance' && $dateCarbon->isToday()) {
                            $status = 'in_progress';
                        }
                        
                        // Only add active/upcoming maintenances (scheduled or in_progress)
                        if (in_array($status, ['scheduled', 'in_progress'])) {
                            $maintenances->push((object)[
                                'car_id' => $car->id,
                                'car' => $car,
                                'start_date' => $dateCarbon,
                                'end_date' => $dateCarbon, // Default to start date (single day event)
                                'maintenance_type' => $m['description'] ?? 'Servis Berkala',
                                'status' => $status,
                                'cost' => (float) ($m['cost'] ?? 0),
                            ]);
                        }
                    }
                }
            }
        }
        $maintenances = $maintenances->sortBy('start_date');

        return view('admin.operational.schedule.index', compact('cars', 'bookings', 'maintenances'));
    }
}
