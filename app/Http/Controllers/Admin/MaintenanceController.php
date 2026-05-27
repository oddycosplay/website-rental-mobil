<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Store;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MaintenanceController extends Controller
{
    public function index()
    {
        $cars = Car::with(['branch'])->get();
        $maintenances = collect();

        foreach ($cars as $car) {
            // Check if $car->maintenances is an array or JSON string
            $records = $car->maintenances;
            if (is_string($records)) {
                $records = json_decode($records, true);
            }
            
            if (is_array($records)) {
                foreach ($records as $index => $m) {
                    $mDate = $m['maintenance_date'] ?? null;
                    if ($mDate) {
                        $dateCarbon = Carbon::parse($mDate);
                        
                        // Determine status
                        $status = 'completed';
                        if ($dateCarbon->isFuture()) {
                            $status = 'scheduled';
                        } elseif ($car->status === 'maintenance' && $dateCarbon->isToday()) {
                            $status = 'in_progress';
                        }

                        $maintenances->push((object)[
                            'id' => $car->id . '_' . $index,
                            'car' => $car,
                            'branch' => $car->branch ?: (object)['name' => 'Garasi Siliwangi'],
                            'start_date' => $dateCarbon,
                            'end_date' => $dateCarbon,
                            'maintenance_type' => $m['description'] ?? 'Servis Berkala',
                            'status' => $status,
                            'cost' => (float) ($m['cost'] ?? 0),
                            'attachment' => $m['attachment'] ?? null,
                        ]);
                    }
                }
            }
        }

        $maintenances = $maintenances->sortByDesc('start_date');

        return view('admin.operational.maintenance.index', compact('maintenances'));
    }

    public function create()
    {
        $cars = Car::all();
        $branches = Store::all();
        return view('admin.operational.maintenance.create', compact('cars', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'branch_id' => 'required|exists:stores,id',
            'start_date' => 'required|date',
            'maintenance_type' => 'required|string',
            'amount' => 'nullable|numeric',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        $car = Car::findOrFail($request->car_id);
        
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('maintenances', 'public');
        }

        $newRecord = [
            'maintenance_date' => $request->start_date,
            'cost' => (float) ($request->amount ?? 0),
            'description' => $request->maintenance_type . ($request->description ? ': ' . $request->description : ''),
            'attachment' => $attachmentPath,
        ];

        $records = $car->maintenances;
        if (is_string($records)) {
            $records = json_decode($records, true);
        }
        if (!is_array($records)) {
            $records = [];
        }

        $records[] = $newRecord;
        $car->maintenances = $records;
        
        // Automatically change car status to maintenance
        $car->status = 'maintenance';
        $car->save();

        return redirect()->route('admin.maintenances.index')->with('success', 'Maintenance record created successfully.');
    }

    public function edit($id)
    {
        $parts = explode('_', $id);
        if (count($parts) < 2) {
            abort(404);
        }
        
        $carId = $parts[0];
        $index = $parts[1];

        $car = Car::findOrFail($carId);
        $records = $car->maintenances;
        if (is_string($records)) {
            $records = json_decode($records, true);
        }

        if (!is_array($records) || !isset($records[$index])) {
            abort(404);
        }

        $m = $records[$index];
        $dateCarbon = Carbon::parse($m['maintenance_date'] ?? now());

        $maintenance = (object)[
            'id' => $id,
            'car_id' => $car->id,
            'car' => $car,
            'branch_id' => $car->store_id,
            'start_date' => $dateCarbon,
            'end_date' => $dateCarbon,
            'maintenance_type' => $m['description'] ?? 'Servis Berkala',
            'status' => 'completed',
            'cost' => (float) ($m['cost'] ?? 0),
            'attachment' => $m['attachment'] ?? null,
            'description' => $m['description'] ?? '',
        ];

        $cars = Car::all();
        $branches = Store::all();

        return view('admin.operational.maintenance.edit', compact('maintenance', 'cars', 'branches'));
    }

    public function update(Request $request, $id)
    {
        $parts = explode('_', $id);
        if (count($parts) < 2) {
            abort(404);
        }
        
        $carId = $parts[0];
        $index = $parts[1];

        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'branch_id' => 'required|exists:stores,id',
            'start_date' => 'required|date',
            'maintenance_type' => 'required|string',
            'amount' => 'nullable|numeric',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        $car = Car::findOrFail($carId);
        $records = $car->maintenances;
        if (is_string($records)) {
            $records = json_decode($records, true);
        }

        if (!is_array($records) || !isset($records[$index])) {
            abort(404);
        }

        $attachmentPath = $records[$index]['attachment'] ?? null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('maintenances', 'public');
        }

        $records[$index] = [
            'maintenance_date' => $request->start_date,
            'cost' => (float) ($request->amount ?? 0),
            'description' => $request->maintenance_type . ($request->description ? ': ' . $request->description : ''),
            'attachment' => $attachmentPath,
        ];

        $car->maintenances = $records;
        $car->save();

        return redirect()->route('admin.maintenances.index')->with('success', 'Maintenance record updated successfully.');
    }

    public function destroy($id)
    {
        $parts = explode('_', $id);
        if (count($parts) < 2) {
            abort(404);
        }
        
        $carId = $parts[0];
        $index = $parts[1];

        $car = Car::findOrFail($carId);
        $records = $car->maintenances;
        if (is_string($records)) {
            $records = json_decode($records, true);
        }

        if (is_array($records) && isset($records[$index])) {
            unset($records[$index]);
            $car->maintenances = array_values($records);
            $car->save();
        }

        return redirect()->route('admin.maintenances.index')->with('success', 'Maintenance record deleted successfully.');
    }
}
