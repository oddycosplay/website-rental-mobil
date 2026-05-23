<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CarMaintenance;
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
            
        // Get active maintenances
        $maintenances = CarMaintenance::with(['car'])
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->orderBy('start_date')
            ->get();

        return view('admin.operational.schedule.index', compact('cars', 'bookings', 'maintenances'));
    }
}
