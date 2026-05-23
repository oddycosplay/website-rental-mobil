<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'booking_category')) {
                $table->string('booking_category')->default('pribadi')->after('booking_type');
            }
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('bookings', 'booking_category')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn('booking_category');
            });
        }
    }
};
