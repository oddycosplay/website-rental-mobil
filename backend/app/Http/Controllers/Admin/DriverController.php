<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Store;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::with(['branch', 'user'])
            ->withCount(['schedules as total_trips' => function($query) {
                $query->where('status', 'completed');
            }])
            ->latest()
            ->paginate(12);

        $stats = [
            'total_drivers' => Driver::count(),
            'available_drivers' => Driver::where('is_available', true)->count(),
            'on_duty_drivers' => Driver::where('is_available', false)->where('status', 'active')->count(),
            'off_drivers' => Driver::where('status', 'inactive')->count(),
        ];

        $branches = Store::all();

        return view('admin.drivers.index', compact('drivers', 'stats', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'phone' => 'required|string|max:20',
            'license_number' => 'required|string|max:50',
            'daily_fee' => 'required|numeric',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['status'] = 'active';
        $data['is_available'] = true;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('drivers', 'public');
        }

        Driver::create($data);

        return redirect()->back()->with('success', 'Supir berhasil ditambahkan!');
    }

    public function show(Driver $driver)
    {
        $driver->load(['branch', 'user']);
        $driver->total_trips = $driver->schedules()->where('status', 'completed')->count();
        $driver->recent_trips = Booking::where('driver_id', $driver->id)
            ->with('car')
            ->latest()
            ->take(5)
            ->get();

        return response()->json($driver);
    }
}
