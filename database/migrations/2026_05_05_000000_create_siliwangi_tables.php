<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. TAMBAH KOLOM KE USERS (Jika belum ada)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->unique()->after('email');
                $table->string('avatar')->nullable()->after('password');
                $table->enum('status', ['active', 'inactive'])->default('active')->after('avatar');
                $table->timestamp('last_login')->nullable()->after('status');
            }
        });

        // 2. STORES (Renamed from branches)
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

        // 3. MASTER MOBIL (Merged brand, type, images, locations, maintenances, inspections)
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->string('car_name');
            $table->string('slug')->unique();
            $table->string('plate_number', 20)->unique();
            $table->year('year');
            $table->string('color', 50)->nullable();
            $table->integer('seat')->default(4);
            $table->enum('transmission', ['Manual', 'Automatic']);
            $table->enum('fuel_type', ['Bensin', 'Diesel', 'Listrik']);
            $table->decimal('daily_price', 15, 2);
            $table->decimal('monthly_price', 15, 2);
            $table->decimal('late_fee', 15, 2);
            $table->string('thumbnail')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
            $table->boolean('is_available')->default(true);
            $table->boolean('featured')->default(false);

            // Merged car_brands columns:
            $table->string('brand_name');
            $table->string('brand_slug');
            $table->string('brand_logo')->nullable();

            // Merged car_types columns:
            $table->string('type_name');
            $table->text('type_description')->nullable();

            // Merged car_images:
            $table->json('images')->nullable();

            // Merged car_locations:
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('speed', 5, 2)->nullable();
            $table->string('location_address')->nullable();
            $table->json('location_raw_data')->nullable();

            // Merged car_maintenances:
            $table->json('maintenances')->nullable();

            // Merged car_inspections:
            $table->json('inspections')->nullable();

            $table->timestamps();
        });


        // 4. CUSTOMERS
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 20);
            $table->string('identity_number', 50)->nullable();
            $table->string('identity_photo')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // 5. DRIVERS
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->string('name');
            $table->string('phone', 20);
            $table->text('address')->nullable();
            $table->string('license_number', 50)->nullable();
            $table->string('photo')->nullable();
            $table->decimal('daily_fee', 15, 2);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        // 8. PROMOS (Harus sebelum bookings)
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('title');
            $table->text('description')->nullable();
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

        // 6. BOOKINGS
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 100)->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('promo_id')->nullable()->constrained('promos')->nullOnDelete();
            
            $table->enum('booking_type', ['daily', 'monthly'])->default('daily');
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
            
            $table->timestamps();
        });

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
            $table->json('payment_logs')->nullable(); // Consolidated logs array
            $table->timestamps();
        });

        // 7b. PAYMENT LOGS (Consolidated into payments.payment_logs)
        // No separate table needed anymore

        // 9. REVIEWS
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->integer('rating')->default(5);
            $table->text('review')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('driver_schedules');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('promos');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('cars');
        Schema::dropIfExists('stores');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'avatar', 'status', 'last_login']);
        });
    }
};
