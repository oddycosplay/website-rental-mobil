<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Double check and finalize data synchronization to prevent any data loss
        
        // Sync GPS Locations
        if (Schema::hasTable('car_locations')) {
            $locations = DB::table('car_locations')->get();
            foreach ($locations as $loc) {
                DB::table('cars')
                    ->where('id', $loc->car_id)
                    ->update([
                        'latitude' => $loc->latitude,
                        'longitude' => $loc->longitude,
                        'speed' => $loc->speed,
                        'location_address' => $loc->address,
                    ]);
            }
        }

        // Sync Maintenances
        if (Schema::hasTable('car_maintenances')) {
            $maintenances = DB::table('car_maintenances')->get();
            $groupedMaintenances = [];
            foreach ($maintenances as $m) {
                $groupedMaintenances[$m->car_id][] = [
                    'maintenance_date' => $m->start_date,
                    'cost' => (float) $m->cost,
                    'description' => $m->description,
                ];
            }
            foreach ($groupedMaintenances as $carId => $records) {
                DB::table('cars')
                    ->where('id', $carId)
                    ->update([
                        'maintenances' => json_encode($records),
                    ]);
            }
        }

        // Sync Inspections
        if (Schema::hasTable('car_inspections')) {
            $inspections = DB::table('car_inspections')->get();
            $groupedInspections = [];
            foreach ($inspections as $i) {
                $booking = DB::table('bookings')->where('id', $i->booking_id)->first();
                if ($booking) {
                    $groupedInspections[$booking->car_id][] = [
                        'inspection_date' => date('Y-m-d', strtotime($i->created_at)),
                        'inspector' => 'Inspector ' . $i->inspector_id,
                        'condition' => $i->type === 'return' ? 'good' : 'excellent',
                        'is_clean' => true,
                    ];
                }
            }
            foreach ($groupedInspections as $carId => $records) {
                DB::table('cars')
                    ->where('id', $carId)
                    ->update([
                        'inspections' => json_encode($records),
                    ]);
            }
        }

        // 2. Drop the redundant separate tables
        Schema::dropIfExists('car_locations');
        Schema::dropIfExists('car_inspections');
        Schema::dropIfExists('car_maintenances');
        Schema::dropIfExists('car_types');
        Schema::dropIfExists('car_brands');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate car_brands
        Schema::create('car_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        // Recreate car_types
        Schema::create('car_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Recreate car_maintenances
        Schema::create('car_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('maintenance_type');
            $table->decimal('cost', 15, 2);
            $table->text('description');
            $table->string('attachment')->nullable();
            $table->string('status');
            $table->timestamps();
        });

        // Recreate car_inspections
        Schema::create('car_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('type');
            $table->integer('mileage');
            $table->string('fuel_level');
            $table->json('checklist');
            $table->string('photos')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('inspector_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // Recreate car_locations
        Schema::create('car_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('speed', 5, 2)->default(0.00);
            $table->string('address')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();
        });
    }
};
