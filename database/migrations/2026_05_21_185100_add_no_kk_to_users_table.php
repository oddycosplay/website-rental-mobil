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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'no_kk')) {
                $table->string('no_kk', 50)->nullable()->after('nik');
            }
            if (!Schema::hasColumn('users', 'kk_photo')) {
                $table->string('kk_photo')->nullable()->after('no_kk');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'no_kk')) {
                $table->dropColumn('no_kk');
            }
            if (Schema::hasColumn('users', 'kk_photo')) {
                $table->dropColumn('kk_photo');
            }
        });
    }
};
