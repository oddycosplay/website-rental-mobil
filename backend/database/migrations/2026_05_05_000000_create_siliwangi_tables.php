<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. STORES
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->text('google_maps')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Add foreign key constraint to users table for store_id
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('store_id')->references('id')->on('stores')->nullOnDelete();
        });

        // 2. MASTER MOBIL (Merged brand, type, images, locations, maintenances, inspections)
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->string('car_name');
            $table->string('slug')->unique();
            $table->string('plate_number', 20)->unique();
            $table->year('year');
            $table->string('color', 50)->nullable();
            $table->integer('seat')->default(4);
            $table->string('transmission', 50);
            $table->string('fuel_type', 50);
            $table->decimal('daily_price', 15, 2);
            $table->decimal('monthly_price', 15, 2);
            $table->decimal('driver_daily_price', 15, 2)->default(0);
            $table->boolean('is_call_for_price')->default(false);
            $table->decimal('late_fee', 15, 2)->default(0);
            $table->string('thumbnail')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
            $table->boolean('is_available')->default(true);
            $table->boolean('featured')->default(false);
            $table->integer('mileage')->default(0);
            $table->string('category', 50)->default('perusahaan');
            $table->date('stnk_expiry')->nullable();
            $table->date('tax_expiry')->nullable();

            // Brand Data (Merged 1:1)
            $table->string('brand_name');
            $table->string('brand_slug');
            $table->string('brand_logo')->nullable();

            // Type Data (Merged 1:1)
            $table->string('type_name');
            $table->text('type_description')->nullable();

            // Gallery Images (Merged 1:N JSON)
            $table->json('images')->nullable();

            // Current GPS Location (Merged 1:1)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('speed', 5, 2)->nullable();
            $table->string('location_address')->nullable();
            $table->json('location_raw_data')->nullable();

            // Service Logs (Merged 1:N JSON)
            $table->json('maintenances')->nullable();

            $table->timestamps();
        });

        // 3. DRIVERS
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->string('name');
            $table->string('phone', 20);
            $table->text('address')->nullable();
            $table->string('license_number', 50)->nullable();
            $table->string('photo')->nullable();
            $table->decimal('daily_fee', 15, 2)->default(150000.00);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        // 4. PROMOS
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('title');
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('discount_value', 15, 2);
            $table->decimal('minimum_transaction', 15, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('quota')->default(0);
            $table->integer('used')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 5. BOOKINGS (Consolidated)
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 100)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('promo_id')->nullable()->constrained('promos')->nullOnDelete();
            
            $table->string('rental_type', 20)->default('daily');
            $table->string('rental_category', 50)->nullable();
            $table->string('area', 100)->nullable();
            $table->boolean('with_driver')->default(false);
            $table->string('driver_name')->nullable();
            $table->dateTime('pickup_date');
            $table->dateTime('return_date');
            $table->text('pickup_location')->nullable();
            $table->text('return_location')->nullable();
            
            $table->integer('total_day');
            $table->decimal('price', 15, 2);
            $table->decimal('driver_price', 15, 2)->default(0);
            $table->decimal('extra_price', 15, 2)->default(0);
            $table->decimal('late_fee', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            $table->decimal('dp_amount', 15, 2)->default(0);
            $table->decimal('remaining_payment', 15, 2)->default(0);
            
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])->default('unpaid');
            $table->enum('booking_status', ['pending', 'confirmed', 'ongoing', 'completed', 'cancelled', 'expired'])->default('pending');
            $table->text('notes')->nullable();
            $table->dateTime('expired_at')->nullable();
            
            // Guest Checkout Columns
            $table->string('guest_token', 100)->nullable()->unique();
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone', 20)->nullable();
            $table->string('ktp_path')->nullable();
            $table->string('sim_path')->nullable();
            $table->string('delivery_type', 50)->nullable();
            $table->string('pickup_type', 50)->nullable();
            $table->decimal('delivery_fee', 15, 2)->default(0);
            $table->decimal('pickup_fee', 15, 2)->default(0);
            $table->decimal('ojol_fee', 15, 2)->default(0);
            $table->timestamps();
        });

        // 6. OPERATIONALS (Inspeksi Kendaraan)
        Schema::create('operationals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->string('inspector_name');
            $table->enum('inspection_type', ['pre_rental', 'post_rental']);
            $table->timestamp('inspected_at')->nullable();
            $table->integer('odometer_km')->default(0);
            $table->enum('fuel_level', ['full', 'three_quarter', 'half', 'quarter', 'empty']);
            $table->json('exterior')->nullable();
            $table->json('interior')->nullable();
            $table->json('equipment')->nullable();
            $table->json('engine')->nullable();
            $table->json('photos')->nullable();
            $table->json('fuel_photos')->nullable();
            $table->boolean('damage_found')->default(false);
            $table->text('damage_description')->nullable();
            $table->decimal('damage_cost', 15, 2)->default(0);
            $table->decimal('dirty_fine', 15, 2)->default(0);
            $table->decimal('fuel_fine', 15, 2)->default(0);
            $table->json('damage_photos')->nullable();
            $table->boolean('customer_confirmed')->default(false);
            $table->text('customer_note')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->timestamps();
        });

        // 7. DRIVER SCHEDULES
        Schema::create('driver_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });

        // 7. PAYMENTS
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->string('payment_code', 100)->unique();
            $table->string('payment_method', 50)->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('snap_token')->nullable();
            $table->decimal('gross_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->enum('payment_status', ['pending', 'success', 'failed', 'expired', 'refund'])->default('pending');
            $table->dateTime('payment_date')->nullable();
            $table->string('proof_payment')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->json('payment_logs')->nullable();
            $table->timestamps();
        });

        // 8. EXPENSES
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->string('category', 100);
            $table->decimal('amount', 15, 2);
            $table->string('description', 255)->nullable();
            $table->string('attachment', 255)->nullable();
            $table->date('date');
            $table->timestamps();
        });

        // 9. REVIEWS
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->tinyInteger('rating')->default(5);
            $table->text('review')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('operationals');
        Schema::dropIfExists('driver_schedules');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('promos');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('cars');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
        });

        Schema::dropIfExists('stores');
    }
};
