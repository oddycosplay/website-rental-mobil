<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign key checks
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        } else {
            Schema::disableForeignKeyConstraints();
        }

        // 1. Recreate the customers table
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('nik', 50)->nullable();
            $table->string('sim_number', 50)->nullable();
            $table->string('ktp_image')->nullable();
            $table->string('sim_image')->nullable();
            $table->string('ktp_path')->nullable();
            $table->string('sim_path')->nullable();
            $table->string('no_kk', 50)->nullable();
            $table->string('kk_photo')->nullable();
            $table->string('nip_nim', 50)->nullable();
            $table->string('id_card_photo')->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->string('customer_status', 50)->nullable()->default('pending');
            $table->text('address')->nullable();
            $table->string('date_of_birth', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Data Migration: users (role customer) -> customers
        $userCustomerMap = [];
        $customerUsers = DB::table('users')->get();
        foreach ($customerUsers as $user) {
            // Check if user is a customer by role or has customer details
            $isCustomer = false;
            if (isset($user->role) && $user->role === 'customer') {
                $isCustomer = true;
            } elseif (!empty($user->nik) || !empty($user->sim_number)) {
                $isCustomer = true;
            }

            if ($isCustomer) {
                $customerId = DB::table('customers')->insertGetId([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'nik' => $user->nik ?? null,
                    'sim_number' => $user->sim_number ?? null,
                    'ktp_image' => $user->ktp_image ?? null,
                    'sim_image' => $user->sim_image ?? null,
                    'ktp_path' => $user->ktp_path ?? null,
                    'sim_path' => $user->sim_path ?? null,
                    'no_kk' => $user->no_kk ?? null,
                    'kk_photo' => $user->kk_photo ?? null,
                    'nip_nim' => $user->nip_nim ?? null,
                    'id_card_photo' => $user->id_card_photo ?? null,
                    'pekerjaan' => $user->pekerjaan ?? null,
                    'customer_status' => $user->customer_status ?? 'pending',
                    'address' => $user->address ?? null,
                    'date_of_birth' => $user->date_of_birth ?? null,
                    'is_active' => $user->is_active ?? true,
                    'created_at' => $user->created_at ?? now(),
                    'updated_at' => $user->updated_at ?? now(),
                ]);

                $userCustomerMap[$user->id] = $customerId;
            }
        }

        // 3. Re-link bookings table (user_id -> customer_id)
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('bookings', 'customer_id')) {
                    $table->foreignId('customer_id')->nullable()->after('booking_code')->constrained('customers')->cascadeOnDelete();
                }
            });

            // Populate customer_id from userCustomerMap
            $bookings = DB::table('bookings')->get();
            foreach ($bookings as $booking) {
                if (isset($booking->user_id) && isset($userCustomerMap[$booking->user_id])) {
                    DB::table('bookings')->where('id', $booking->id)->update([
                        'customer_id' => $userCustomerMap[$booking->user_id]
                    ]);
                }
            }

            // Drop old user_id column
            Schema::table('bookings', function (Blueprint $table) {
                if (DB::getDriverName() !== 'sqlite' && Schema::hasColumn('bookings', 'user_id')) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
            });
        }

        // 4. Re-link reviews table (user_id -> customer_id)
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                if (!Schema::hasColumn('reviews', 'customer_id')) {
                    $table->foreignId('customer_id')->nullable()->after('booking_id')->constrained('customers')->cascadeOnDelete();
                }
            });

            // Populate customer_id from userCustomerMap
            $reviews = DB::table('reviews')->get();
            foreach ($reviews as $review) {
                if (isset($review->user_id) && isset($userCustomerMap[$review->user_id])) {
                    DB::table('reviews')->where('id', $review->id)->update([
                        'customer_id' => $userCustomerMap[$review->user_id]
                    ]);
                }
            }

            // Drop old user_id column
            Schema::table('reviews', function (Blueprint $table) {
                if (DB::getDriverName() !== 'sqlite' && Schema::hasColumn('reviews', 'user_id')) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
            });
        }

        // 5. Drop customer-specific columns from users table
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('users', function (Blueprint $table) {
                $columnsToDrop = [
                    'nik', 'sim_number', 'ktp_image', 'sim_image',
                    'ktp_path', 'sim_path', 'no_kk', 'kk_photo',
                    'nip_nim', 'id_card_photo', 'pekerjaan', 'customer_status',
                    'address', 'date_of_birth', 'identity_number', 'identity_photo',
                    'kk_image', 'npwp_image', 'pelajar_image', 'mahasiswa_image'
                ];

                foreach ($columnsToDrop as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        // Re-enable foreign key checks
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        } else {
            Schema::enableForeignKeyConstraints();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reversing this structural change is not supported in simple rollback.
    }
};
