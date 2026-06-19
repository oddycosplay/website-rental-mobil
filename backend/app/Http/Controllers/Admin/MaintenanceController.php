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
            $records = $car->maintenances;
            if (is_string($records)) {
                $records = json_decode($records, true);
            }
            
            if (is_array($records)) {
                foreach ($records as $index => $m) {
                    $mDate = $m['maintenance_date'] ?? null;
                    if ($mDate) {
                        $dateCarbon = Carbon::parse($mDate);
                        
                        // Parse status with backward-compatible fallback
                        $status = $m['status'] ?? null;
                        if (!$status) {
                            $status = 'completed';
                            if ($dateCarbon->isFuture()) {
                                $status = 'scheduled';
                            } elseif ($car->status === 'maintenance' && $dateCarbon->isToday()) {
                                $status = 'in_progress';
                            }
                        }

                        // Parse end date
                        $endDateStr = $m['end_date'] ?? null;
                        $endDateCarbon = $endDateStr ? Carbon::parse($endDateStr) : null;

                        // Parse branch
                        $branchId = $m['branch_id'] ?? $car->store_id;
                        $branch = Store::query()->find($branchId) ?: ($car->branch ?: (object)['name' => 'Garasi Siliwangi']);

                        // Parse type & description with backward compatibility
                        $mType = $m['maintenance_type'] ?? null;
                        $mDesc = $m['description'] ?? '';
                        if (!$mType) {
                            if (str_contains($mDesc, ': ')) {
                                $parts = explode(': ', $mDesc, 2);
                                $mType = $parts[0];
                                $mDesc = $parts[1];
                            } else {
                                $mType = $mDesc ?: 'Servis Berkala';
                                $mDesc = '';
                            }
                        }

                        $maintenances->push((object)[
                            'id' => $car->id . '_' . $index,
                            'car' => $car,
                            'branch' => $branch,
                            'start_date' => $dateCarbon,
                            'end_date' => $endDateCarbon,
                            'maintenance_type' => $mType,
                            'status' => $status,
                            'cost' => (float) ($m['cost'] ?? 0),
                            'attachment' => $m['attachment'] ?? null,
                            'description' => $mDesc,
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
            'end_date' => 'nullable|date|after_or_equal:start_date',
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

        // Determine status automatically
        $status = 'in_progress';
        if ($request->end_date) {
            $status = 'completed';
        } elseif (Carbon::parse($request->start_date)->isFuture()) {
            $status = 'scheduled';
        }

        $newRecord = [
            'maintenance_date' => $request->start_date,
            'end_date' => $request->end_date,
            'cost' => (float) ($request->amount ?? 0),
            'maintenance_type' => $request->maintenance_type,
            'description' => $request->description ?? '',
            'status' => $status,
            'branch_id' => $request->branch_id,
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
        
        // Auto update car status
        if ($status === 'in_progress') {
            $car->status = 'maintenance';
        } elseif ($status === 'completed') {
            $car->status = 'available';
        }
        $car->save();

        return redirect()->route('admin.maintenances.index')->with('success', 'Maintenance record created successfully.');
    }

    public function edit(string $id)
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
        $endDateStr = $m['end_date'] ?? null;
        $endDateCarbon = $endDateStr ? Carbon::parse($endDateStr) : null;

        // Parse type & description with backward compatibility
        $mType = $m['maintenance_type'] ?? null;
        $mDesc = $m['description'] ?? '';
        if (!$mType) {
            if (str_contains($mDesc, ': ')) {
                $parts = explode(': ', $mDesc, 2);
                $mType = $parts[0];
                $mDesc = $parts[1];
            } else {
                $mType = $mDesc ?: 'Servis Berkala';
                $mDesc = '';
            }
        }

        // Determine status
        $status = $m['status'] ?? null;
        if (!$status) {
            $status = 'completed';
            if ($dateCarbon->isFuture()) {
                $status = 'scheduled';
            } elseif ($car->status === 'maintenance' && $dateCarbon->isToday()) {
                $status = 'in_progress';
            }
        }

        $maintenance = (object)[
            'id' => $id,
            'car_id' => $car->id,
            'car' => $car,
            'branch_id' => $m['branch_id'] ?? $car->store_id,
            'start_date' => $dateCarbon,
            'end_date' => $endDateCarbon,
            'maintenance_type' => $mType,
            'status' => $status,
            'cost' => (float) ($m['cost'] ?? 0),
            'attachment' => $m['attachment'] ?? null,
            'description' => $mDesc,
            'created_at' => $car->created_at ?? now(),
            'updated_at' => $car->updated_at ?? now(),
        ];

        $cars = Car::all();
        $branches = Store::all();

        return view('admin.operational.maintenance.edit', compact('maintenance', 'cars', 'branches'));
    }

    public function update(Request $request, string $id)
    {
        $parts = explode('_', $id);
        if (count($parts) < 2) {
            abort(404);
        }
        
        $carId = $parts[0];
        $index = $parts[1];

        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'status' => 'required|string|in:scheduled,in_progress,completed',
            'end_date' => 'nullable|date',
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

        // Preserved fields from old entry
        $mDate = $records[$index]['maintenance_date'] ?? date('Y-m-d');
        $mType = $records[$index]['maintenance_type'] ?? null;
        $mBranch = $records[$index]['branch_id'] ?? $car->store_id;

        if (!$mType) {
            $oldDesc = $records[$index]['description'] ?? '';
            if (str_contains($oldDesc, ': ')) {
                $partsDesc = explode(': ', $oldDesc, 2);
                $mType = $partsDesc[0];
            } else {
                $mType = $oldDesc ?: 'Servis Berkala';
            }
        }

        $records[$index] = [
            'maintenance_date' => $mDate,
            'end_date' => $request->end_date,
            'cost' => (float) ($request->amount ?? 0),
            'maintenance_type' => $mType,
            'description' => $request->description ?? '',
            'status' => $request->status,
            'branch_id' => $mBranch,
            'attachment' => $attachmentPath,
        ];

        $car->maintenances = $records;

        // Auto update car status
        if ($request->status === 'completed') {
            $car->status = 'available';
        } elseif ($request->status === 'in_progress') {
            $car->status = 'maintenance';
        }
        $car->save();

        return redirect()->route('admin.maintenances.index')->with('success', 'Maintenance record updated successfully.');
    }

    public function destroy(string $id)
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
            $status = $records[$index]['status'] ?? 'completed';
            unset($records[$index]);
            $car->maintenances = array_values($records);
            
            // If the deleted record was in_progress, check if any other in_progress is left; if not, revert car status to available
            if ($status === 'in_progress') {
                $hasActiveMaint = false;
                foreach ($car->maintenances as $rm) {
                    if (($rm['status'] ?? '') === 'in_progress') {
                        $hasActiveMaint = true;
                        break;
                    }
                }
                if (!$hasActiveMaint) {
                    $car->status = 'available';
                }
            }
            $car->save();
        }

        return redirect()->route('admin.maintenances.index')->with('success', 'Maintenance record deleted successfully.');
    }
}
