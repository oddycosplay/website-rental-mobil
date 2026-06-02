<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarInspectionController extends Controller
{
    public function index()
    {
        // 1. Ambil data mobil untuk daftar audit manual & dropdown
        $cars = Car::all();

        // 2. Kueri Antrean Inspeksi dari Booking aktif
        // - Booking status 'confirmed' menunggu serah terima (Check-out)
        // - Booking status 'ongoing' menunggu pengembalian (Check-in)
        $activeBookings = Booking::with(['customer', 'car'])
            ->whereIn('booking_status', ['confirmed', 'ongoing'])
            ->orderBy('pickup_date', 'asc')
            ->get();

        // 3. Gabungkan seluruh riwayat inspeksi dari kolom JSON inspections milik mobil
        $inspectionsLog = collect();
        foreach ($cars as $car) {
            $inspections = $car->inspections;
            if (is_array($inspections)) {
                foreach ($inspections as $ins) {
                    $inspectionsLog->push((object)[
                        'car_id' => $car->id,
                        'car_name' => $car->car_name,
                        'plate_number' => $car->plate_number,
                        'thumbnail' => $car->thumbnail,
                        'booking_id' => $ins['booking_id'] ?? null,
                        'booking_code' => $ins['booking_code'] ?? 'TRX-' . ($ins['booking_id'] ?? 'UNKNOWN'),
                        'inspection_date' => $ins['inspection_date'] ?? null,
                        'type' => $ins['type'] ?? 'check-out',
                        'mileage' => $ins['mileage'] ?? 0,
                        'fuel_level' => $ins['fuel_level'] ?? 'full',
                        'checklist' => $ins['checklist'] ?? [],
                        'inspector' => $ins['inspector'] ?? 'System Inspector',
                        'notes' => $ins['notes'] ?? '-',
                    ]);
                }
            }
        }
        
        // Urutkan riwayat berdasarkan tanggal terbaru
        $inspectionsLog = $inspectionsLog->sortByDesc('inspection_date');

        // 4. Hitung Metrik Utama (KPIs)
        $totalInspections = $inspectionsLog->count();
        $pendingInspections = $activeBookings->count();
        $readyCarsCount = Car::query()->where('status', 'available')->where('is_available', true)->count();
        $maintenanceCarsCount = Car::query()->where('status', 'maintenance')->count();

        return view('admin.operational.inspections.index', compact(
            'cars',
            'activeBookings',
            'inspectionsLog',
            'totalInspections',
            'pendingInspections',
            'readyCarsCount',
            'maintenanceCarsCount'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'type' => 'required|in:check-out,check-in',
            'mileage' => 'required|integer|min:0',
            'fuel_level' => 'required|in:empty,quarter,half,three-quarters,full',
            'checklist' => 'required|array',
            'checklist.body' => 'required|in:ok,scratch,dent,damaged',
            'checklist.engine' => 'required|in:ok,noisy,check-engine,needs-service',
            'checklist.interior' => 'required|in:ok,dirty,damaged',
            'checklist.tires' => 'required|in:ok,worn,flat',
            'inspector' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $booking = Booking::with('car')->findOrFail($request->booking_id);
            $car = $booking->car;

            if (!$car) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mobil tidak ditemukan untuk transaksi ini.'
                ], 404);
            }

            // 1. Buat catatan inspeksi baru
            $newInspection = [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code ?? ('TRX-' . $booking->id),
                'inspection_date' => now()->format('Y-m-d H:i:s'),
                'type' => $request->type,
                'mileage' => (int) $request->mileage,
                'fuel_level' => $request->fuel_level,
                'checklist' => [
                    'body' => $request->input('checklist.body'),
                    'engine' => $request->input('checklist.engine'),
                    'interior' => $request->input('checklist.interior'),
                    'tires' => $request->input('checklist.tires'),
                ],
                'inspector' => $request->inspector ?: (auth()->user()->name ?? 'Inspector Lapangan'),
                'notes' => $request->notes ?: '-',
            ];

            // 2. Ambil data inspeksi yang ada & tambahkan catatan baru
            $inspections = $car->inspections ?: [];
            $inspections[] = $newInspection;
            $car->inspections = $inspections;

            // 3. Perbarui Odometer Mobil jika nilai baru lebih besar
            if ((int) $request->mileage > (int) $car->mileage) {
                $car->mileage = (int) $request->mileage;
            }

            // 4. Logika Perubahan Status (Otimatisasi Operasional)
            if ($request->type === 'check-out') {
                // Saat diserahkan ke customer:
                // - Status booking berubah dari confirmed -> ongoing
                // - Status mobil berubah menjadi rented (sedang disewa)
                $booking->booking_status = 'ongoing';
                $car->status = 'rented';
                $car->is_available = false;
            } elseif ($request->type === 'check-in') {
                // Saat dikembalikan oleh customer:
                // - Status booking berubah dari ongoing -> completed
                $booking->booking_status = 'completed';

                // Periksa apakah ada kerusakan berat pada bodi/mesin/ban/interior
                $hasDamage = in_array($request->input('checklist.body'), ['dent', 'damaged']) ||
                             in_array($request->input('checklist.engine'), ['check-engine', 'needs-service']) ||
                             in_array($request->input('checklist.interior'), ['damaged']) ||
                             in_array($request->input('checklist.tires'), ['flat']);

                if ($hasDamage) {
                    // Jika terdeteksi kerusakan berat, alihkan mobil ke bengkel (maintenance)
                    $car->status = 'maintenance';
                    $car->is_available = false;
                } else {
                    // Jika aman/mulus, kembalikan status mobil ke tersedia (available)
                    $car->status = 'available';
                    $car->is_available = true;
                }
            }

            // Simpan perubahan data mobil & booking
            $car->save();
            $booking->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $request->type === 'check-out' 
                    ? 'Inspeksi Serah Terima (Check-out) berhasil disimpan. Mobil kini berstatus ACTIVE.'
                    : 'Inspeksi Pengembalian (Check-in) berhasil disimpan. Mobil telah diproses.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing car inspection: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
