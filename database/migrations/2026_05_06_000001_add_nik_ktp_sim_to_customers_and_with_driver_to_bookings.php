<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom nik, ktp_image, sim_image, status ke tabel customers.
     * Juga tambah with_driver ke tabel bookings untuk tracking tipe layanan.
     */
    public function up(): void
    {
        // Tambah kolom ke customers
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'nik')) {
                $table->string('nik', 50)->nullable()->after('identity_number');
            }
            if (!Schema::hasColumn('customers', 'ktp_image')) {
                $table->string('ktp_image')->nullable()->after('identity_photo');
            }
            if (!Schema::hasColumn('customers', 'sim_image')) {
                $table->string('sim_image')->nullable()->after('ktp_image');
            }
            if (!Schema::hasColumn('customers', 'status')) {
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('sim_image');
            }
        });

        // Tambah with_driver ke bookings
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'with_driver')) {
                $table->boolean('with_driver')->default(false)->after('booking_type');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn(['nik', 'ktp_image', 'sim_image', 'status']);
            });
        }

        if (Schema::hasTable('bookings')) {
            if (Schema::hasColumn('bookings', 'with_driver')) {
                Schema::table('bookings', function (Blueprint $table) {
                    $table->dropColumn('with_driver');
                });
            }
        }
    }
};
