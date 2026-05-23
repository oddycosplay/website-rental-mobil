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
        Schema::table('cars', function (Blueprint $table) {
            $table->decimal('driver_daily_price', 15, 2)->default(0)->after('daily_price');
            $table->boolean('is_call_for_price')->default(false)->after('driver_daily_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            if (Schema::hasColumn('cars', 'driver_daily_price')) {
                $table->dropColumn('driver_daily_price');
            }
            if (Schema::hasColumn('cars', 'is_call_for_price')) {
                $table->dropColumn('is_call_for_price');
            }
        });
    }
};
