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
            // Ensure core KTP and SIM fields are present
            if (!Schema::hasColumn('users', 'nik')) {
                $table->string('nik', 50)->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'ktp_image')) {
                $table->string('ktp_image')->nullable()->after('nik');
            }
            if (!Schema::hasColumn('users', 'sim_number')) {
                $table->string('sim_number', 50)->nullable()->after('ktp_image');
            }
            if (!Schema::hasColumn('users', 'sim_image')) {
                $table->string('sim_image')->nullable()->after('sim_number');
            }

            // Add new custom KK, ID Employee/Student, Job, and Customer Status fields
            if (!Schema::hasColumn('users', 'nip_nim')) {
                $table->string('nip_nim', 50)->nullable()->after('kk_photo');
            }
            if (!Schema::hasColumn('users', 'id_card_photo')) {
                $table->string('id_card_photo')->nullable()->after('nip_nim');
            }
            if (!Schema::hasColumn('users', 'pekerjaan')) {
                $table->string('pekerjaan', 100)->nullable()->after('id_card_photo');
            }
            if (!Schema::hasColumn('users', 'customer_status')) {
                $table->string('customer_status', 50)->nullable()->default('pending')->after('pekerjaan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['nip_nim', 'id_card_photo', 'pekerjaan', 'customer_status'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
