<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('location_surveys', function (Blueprint $table) {
            $table->dropColumn(['road_condition', 'access_condition', 'area_safety', 'distance_km', 'latitude', 'longitude']);
            
            $table->json('residence_status')->nullable()->after('address');
            $table->json('job_status')->nullable()->after('residence_status');
            $table->json('neighbor_interview')->nullable()->after('job_status');
        });

        Schema::table('vehicle_inspections', function (Blueprint $table) {
            $table->decimal('dirty_fine', 12, 2)->nullable()->after('damage_cost');
            $table->decimal('fuel_fine', 12, 2)->nullable()->after('dirty_fine');
            $table->json('fuel_photos')->nullable()->after('photos');
        });
    }

    public function down(): void
    {
        Schema::table('location_surveys', function (Blueprint $table) {
            $table->json('road_condition')->nullable();
            $table->json('access_condition')->nullable();
            $table->json('area_safety')->nullable();
            $table->decimal('distance_km', 6, 2)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->dropColumn(['residence_status', 'job_status', 'neighbor_interview']);
        });

        Schema::table('vehicle_inspections', function (Blueprint $table) {
            $table->dropColumn(['dirty_fine', 'fuel_fine', 'fuel_photos']);
        });
    }
};
