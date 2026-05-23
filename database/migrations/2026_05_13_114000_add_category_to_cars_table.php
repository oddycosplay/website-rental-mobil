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
            if (!Schema::hasColumn('cars', 'category')) {
                $table->enum('category', ['pribadi', 'perusahaan', 'both'])->default('both')->after('type_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('cars', 'category')) {
            Schema::table('cars', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }
};
