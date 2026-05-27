<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Disable foreign keys checks during migration
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        } else {
            Schema::disableForeignKeyConstraints();
        }

        // 1. Add all Customer fields to Users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'identity_number')) {
                $table->string('identity_number', 50)->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'identity_photo')) {
                $table->string('identity_photo')->nullable()->after('identity_number');
            }
            if (!Schema::hasColumn('users', 'nik')) {
                $table->string('nik', 50)->nullable()->after('identity_photo');
            }
            if (!Schema::hasColumn('users', 'sim_number')) {
                $table->string('sim_number', 50)->nullable()->after('nik');
            }
            if (!Schema::hasColumn('users', 'ktp_image')) {
                $table->string('ktp_image')->nullable()->after('sim_number');
            }
            if (!Schema::hasColumn('users', 'sim_image')) {
                $table->string('sim_image')->nullable()->after('ktp_image');
            }
            if (!Schema::hasColumn('users', 'kk_image')) {
                $table->string('kk_image')->nullable()->after('sim_image');
            }
            if (!Schema::hasColumn('users', 'npwp_image')) {
                $table->string('npwp_image')->nullable()->after('kk_image');
            }
            if (!Schema::hasColumn('users', 'pelajar_image')) {
                $table->string('pelajar_image')->nullable()->after('npwp_image');
            }
            if (!Schema::hasColumn('users', 'mahasiswa_image')) {
                $table->string('mahasiswa_image')->nullable()->after('pelajar_image');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('mahasiswa_image');
            }
        });

        // 2. Data Migration: Customers -> Users
        $customerUserMap = [];
        if (Schema::hasTable('customers')) {
            $customers = DB::table('customers')->get();
            $defaultPassword = Hash::make('password');

            foreach ($customers as $customer) {
                $userId = $customer->user_id;

                // If customer is not linked to a user, search by email/phone or create one
                if (!$userId) {
                    $existingUser = null;
                    if (!empty($customer->email)) {
                        $existingUser = DB::table('users')->where('email', $customer->email)->first();
                    }
                    if (!$existingUser && !empty($customer->phone)) {
                        $existingUser = DB::table('users')->where('phone', $customer->phone)->first();
                    }

                    if ($existingUser) {
                        $userId = $existingUser->id;
                    } else {
                        // Create a new user
                        $email = !empty($customer->email) ? $customer->email : 'customer_' . $customer->id . '@siliwangi.com';
                        $userId = DB::table('users')->insertGetId([
                            'name' => $customer->name,
                            'email' => $email,
                            'phone' => $customer->phone,
                            'password' => $defaultPassword,
                            'status' => 'active',
                            'created_at' => $customer->created_at ?? now(),
                            'updated_at' => $customer->updated_at ?? now(),
                        ]);

                        // Assign role if Spatie roles table exists
                        if (Schema::hasTable('model_has_roles')) {
                            DB::table('model_has_roles')->insertOrIgnore([
                                'role_id' => 3, // customer role
                                'model_type' => 'App\\Models\\User',
                                'model_id' => $userId,
                            ]);
                        }
                    }
                }

                // Update User details with Customer attributes
                DB::table('users')->where('id', $userId)->update([
                    'identity_number' => $customer->identity_number ?? null,
                    'identity_photo' => $customer->identity_photo ?? null,
                    'nik' => $customer->nik ?? null,
                    'sim_number' => $customer->sim_number ?? null,
                    'ktp_image' => $customer->ktp_image ?? null,
                    'sim_image' => $customer->sim_image ?? null,
                    'kk_image' => $customer->kk_image ?? null,
                    'npwp_image' => $customer->npwp_image ?? null,
                    'pelajar_image' => $customer->pelajar_image ?? null,
                    'mahasiswa_image' => $customer->mahasiswa_image ?? null,
                    'address' => $customer->address ?? null,
                ]);

                $customerUserMap[$customer->id] = $userId;
            }
        }

        // 3. Re-link Bookings (customer_id -> user_id)
        if (Schema::hasTable('bookings')) {
            // Drop old constraint first
            Schema::table('bookings', function (Blueprint $table) {
                if (DB::getDriverName() !== 'sqlite') {
                    $table->dropForeign(['customer_id']);
                }
            });

            // Add user_id column
            Schema::table('bookings', function (Blueprint $table) {
                if (!Schema::hasColumn('bookings', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('booking_code')->constrained('users')->cascadeOnDelete();
                }
            });

            // Populate user_id from customerUserMap
            $bookings = DB::table('bookings')->get();
            foreach ($bookings as $booking) {
                $mappedUserId = $customerUserMap[$booking->customer_id] ?? null;
                if ($mappedUserId) {
                    DB::table('bookings')->where('id', $booking->id)->update(['user_id' => $mappedUserId]);
                }
            }

            // Drop customer_id
            Schema::table('bookings', function (Blueprint $table) {
                if (DB::getDriverName() !== 'sqlite' && Schema::hasColumn('bookings', 'customer_id')) {
                    $table->dropColumn('customer_id');
                }
            });
        }

        // 4. Re-link Reviews (customer_id -> user_id)
        if (Schema::hasTable('reviews')) {
            // Drop old constraint first
            Schema::table('reviews', function (Blueprint $table) {
                if (DB::getDriverName() !== 'sqlite') {
                    $table->dropForeign(['customer_id']);
                }
            });

            // Add user_id column
            Schema::table('reviews', function (Blueprint $table) {
                if (!Schema::hasColumn('reviews', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('booking_id')->constrained('users')->cascadeOnDelete();
                }
            });

            // Populate user_id from customerUserMap
            $reviews = DB::table('reviews')->get();
            foreach ($reviews as $review) {
                $mappedUserId = $customerUserMap[$review->customer_id] ?? null;
                if ($mappedUserId) {
                    DB::table('reviews')->where('id', $review->id)->update(['user_id' => $mappedUserId]);
                }
            }

            // Drop customer_id
            Schema::table('reviews', function (Blueprint $table) {
                if (DB::getDriverName() !== 'sqlite' && Schema::hasColumn('reviews', 'customer_id')) {
                    $table->dropColumn('customer_id');
                }
            });
        }
        
        // 4b. bookings Schema Alignment
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (Schema::hasColumn('bookings', 'booking_category')) {
                    $table->dropColumn('booking_category');
                }
                if (Schema::hasColumn('bookings', 'booking_type')) {
                    $table->dropColumn('booking_type');
                }
                if (!Schema::hasColumn('bookings', 'rental_type')) {
                    $table->string('rental_type', 20)->default('daily')->after('promo_id'); // daily, monthly
                }
                
                // Add guest checkout & docs columns
                if (!Schema::hasColumn('bookings', 'guest_token')) {
                    $table->string('guest_token')->nullable()->after('rental_type');
                }
                if (!Schema::hasColumn('bookings', 'guest_name')) {
                    $table->string('guest_name')->nullable()->after('guest_token');
                }
                if (!Schema::hasColumn('bookings', 'guest_email')) {
                    $table->string('guest_email')->nullable()->after('guest_name');
                }
                if (!Schema::hasColumn('bookings', 'guest_phone')) {
                    $table->string('guest_phone')->nullable()->after('guest_email');
                }
                if (!Schema::hasColumn('bookings', 'ktp_path')) {
                    $table->string('ktp_path')->nullable()->after('guest_phone');
                }
                if (!Schema::hasColumn('bookings', 'sim_path')) {
                    $table->string('sim_path')->nullable()->after('ktp_path');
                }
            });
        }

        // 4c. cars stock removal
        if (Schema::hasTable('cars')) {
            Schema::table('cars', function (Blueprint $table) {
                if (DB::getDriverName() !== 'sqlite' && Schema::hasColumn('cars', 'stock')) {
                    $table->dropColumn('stock');
                }
            });
        }

        // 5. Drop redundant tables
        Schema::dropIfExists('customers');
        Schema::dropIfExists('booking_items');

        // Re-enable constraints
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
        // This is a complex destructive consolidation, going back is not typical, 
        // but we can recreate a blank customers table or skip to avoid data loss.
    }
};
