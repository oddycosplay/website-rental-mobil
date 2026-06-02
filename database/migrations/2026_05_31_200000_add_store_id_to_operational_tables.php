<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('location_surveys', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->after('id')->constrained('stores')->cascadeOnDelete();
        });

        Schema::table('vehicle_inspections', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->after('id')->constrained('stores')->cascadeOnDelete();
        });

        // Sync existing records store_id from their bookings
        DB::statement("UPDATE location_surveys ls JOIN bookings b ON ls.booking_id = b.id SET ls.store_id = b.store_id");
        DB::statement("UPDATE vehicle_inspections vi JOIN bookings b ON vi.booking_id = b.id SET vi.store_id = b.store_id");
    }

    public function down(): void
    {
        Schema::table('location_surveys', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
        });

        Schema::table('vehicle_inspections', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
        });
    }
};
