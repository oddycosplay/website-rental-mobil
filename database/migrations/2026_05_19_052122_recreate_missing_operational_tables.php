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
        // 1. car_brands Table
        if (!Schema::hasTable('car_brands')) {
            Schema::create('car_brands', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('logo')->nullable();
                $table->timestamps();
            });
        }

        // 2. car_types Table
        if (!Schema::hasTable('car_types')) {
            Schema::create('car_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 3. car_maintenances Table
        if (!Schema::hasTable('car_maintenances')) {
            Schema::create('car_maintenances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
                $table->foreignId('store_id')->nullable()->constrained('stores')->cascadeOnDelete();
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->string('maintenance_type');
                $table->decimal('cost', 15, 2)->default(0);
                $table->text('description')->nullable();
                $table->string('attachment')->nullable();
                $table->string('status')->default('scheduled');
                $table->timestamps();
            });
        }

        // 4. car_inspections Table
        if (!Schema::hasTable('car_inspections')) {
            Schema::create('car_inspections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('booking_id')->nullable()->constrained('bookings')->cascadeOnDelete();
                $table->string('type')->nullable();
                $table->integer('mileage')->nullable();
                $table->string('fuel_level')->nullable();
                $table->json('checklist')->nullable();
                $table->json('photos')->nullable();
                $table->text('notes')->nullable();
                $table->foreignId('inspector_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // 5. car_locations Table
        if (!Schema::hasTable('car_locations')) {
            Schema::create('car_locations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->decimal('speed', 5, 2)->nullable();
                $table->string('address')->nullable();
                $table->json('raw_data')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_locations');
        Schema::dropIfExists('car_inspections');
        Schema::dropIfExists('car_maintenances');
        Schema::dropIfExists('car_types');
        Schema::dropIfExists('car_brands');
    }
};
