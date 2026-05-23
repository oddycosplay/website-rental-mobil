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
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'kk_image')) {
                $table->string('kk_image')->nullable()->after('sim_image');
            }
            if (!Schema::hasColumn('customers', 'npwp_image')) {
                $table->string('npwp_image')->nullable()->after('kk_image');
            }
            if (!Schema::hasColumn('customers', 'pelajar_image')) {
                $table->string('pelajar_image')->nullable()->after('npwp_image');
            }
            if (!Schema::hasColumn('customers', 'mahasiswa_image')) {
                $table->string('mahasiswa_image')->nullable()->after('pelajar_image');
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'notes')) {
                $table->text('notes')->nullable()->after('booking_category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn([
                    'kk_image',
                    'npwp_image',
                    'pelajar_image',
                    'mahasiswa_image',
                ]);
            });
        }

        if (Schema::hasTable('bookings')) {
            if (Schema::hasColumn('bookings', 'notes')) {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->dropColumn('notes');
                });
            }
        }
    }
};
