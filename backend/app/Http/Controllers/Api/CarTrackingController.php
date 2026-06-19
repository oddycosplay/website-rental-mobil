<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarTrackingController extends Controller
{
    /**
     * Update car location from GPS device
     * 
     * Endpoint: POST /api/v1/tracking/update
     * Payload: { car_id: 1, latitude: -6.123, longitude: 106.123, speed: 60, api_token: '...' }
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_id' => 'required|exists:cars,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'speed' => 'nullable|numeric',
            'api_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Simple token verification (In production, use Sanctum or hashed keys)
        if ($request->api_token !== config('app.gps_api_token', 'SILIWANGI_GPS_SECRET')) {
            return response()->json(['error' => 'Unauthorized GPS device'], 401);
        }

        $location = CarLocation::create([
            'car_id' => $request->car_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'speed' => $request->speed,
            'raw_data' => $request->all(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Location updated',
            'timestamp' => $location->created_at,
        ]);
    }

    /**
     * Get latest locations for all active cars (for Admin Map)
     */
    public function getLatestLocations()
    {
        $latestLocations = Car::with(['latestLocation'])
            ->whereHas('latestLocation')
            ->get()
            ->map(function ($car) {
                return [
                    'id' => $car->id,
                    'name' => $car->car_name,
                    'plate' => $car->plate_number,
                    'lat' => $car->latestLocation->latitude,
                    'lng' => $car->latestLocation->longitude,
                    'speed' => $car->latestLocation->speed,
                    'updated_at' => $car->latestLocation->created_at->diffForHumans(),
                ];
            });

        return response()->json($latestLocations);
    }
}
