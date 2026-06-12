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

        Schema::table('operationals', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->after('id')->constrained('stores')->cascadeOnDelete();
        });

        // Sync existing records store_id from their bookings
        // Use subquery syntax compatible with both MySQL and SQLite (for testing)
        DB::statement("UPDATE location_surveys SET store_id = (SELECT store_id FROM bookings WHERE bookings.id = location_surveys.booking_id) WHERE booking_id IS NOT NULL");
        DB::statement("UPDATE operationals SET store_id = (SELECT store_id FROM bookings WHERE bookings.id = operationals.booking_id) WHERE booking_id IS NOT NULL");
    }

    public function down(): void
    {
        Schema::table('location_surveys', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
        });

        Schema::table('operationals', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
        });
    }
};
