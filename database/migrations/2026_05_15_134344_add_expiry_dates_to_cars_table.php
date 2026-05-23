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
            $table->date('stnk_expiry')->nullable()->after('plate_number');
            $table->date('tax_expiry')->nullable()->after('stnk_expiry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            if (Schema::hasColumn('cars', 'stnk_expiry')) {
                $table->dropColumn('stnk_expiry');
            }
            if (Schema::hasColumn('cars', 'tax_expiry')) {
                $table->dropColumn('tax_expiry');
            }
        });
    }
};
