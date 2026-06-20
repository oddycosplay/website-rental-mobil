<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = Carbon::now();

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('users')->truncate();
        DB::table('stores')->truncate();
        DB::table('operationals')->truncate();
        if (\Illuminate\Support\Facades\Schema::hasTable('location_surveys')) {
            DB::table('location_surveys')->truncate();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('employees')) {
            DB::table('employees')->truncate();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('car_brands')) {
            DB::table('car_brands')->truncate();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('car_types')) {
            DB::table('car_types')->truncate();
        }
        DB::table('cars')->truncate();
        DB::table('drivers')->truncate();
        DB::table('promos')->truncate();
        DB::table('bookings')->truncate();
        DB::table('payments')->truncate();
        DB::table('reviews')->truncate();
        if (\Illuminate\Support\Facades\Schema::hasTable('car_maintenances')) {
            DB::table('car_maintenances')->truncate();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('car_inspections')) {
            DB::table('car_inspections')->truncate();
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('car_locations')) {
            DB::table('car_locations')->truncate();
        }
        DB::table('expenses')->truncate();
        // 1. Roles
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'super-admin', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'owner', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'customer', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'finance', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'driver', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'name' => 'operasional', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'name' => 'admin', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->call(PermissionSeeder::class);

        // 2. Users
        $password = Hash::make('password'); // Default password
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Super Admin', 'email' => 'admin@siliwangi.com', 'phone' => '081200000001', 'password' => $password, 'status' => 'active', 'store_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Owner', 'email' => 'owner@siliwangi.com', 'phone' => '081200000002', 'password' => $password, 'status' => 'active', 'store_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Siti Finance', 'email' => 'finance@siliwangi.com', 'phone' => '081200000004', 'password' => $password, 'status' => 'active', 'store_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'Asep Supir', 'email' => 'asep@siliwangi.com', 'phone' => '081200000005', 'password' => $password, 'status' => 'active', 'store_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'name' => 'Operasional', 'email' => 'operasional@siliwangi.com', 'phone' => '081200000006', 'password' => $password, 'status' => 'active', 'store_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'name' => 'Staff Admin', 'email' => 'staff.admin@siliwangi.com', 'phone' => '081200000007', 'password' => $password, 'status' => 'active', 'store_id' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('users')->insert([
            ['id' => 3, 'name' => 'Budi Customer', 'email' => 'budi@gmail.com', 'phone' => '081200000003', 'password' => $password, 'status' => 'active', 'store_id' => null, 'nik' => '3171234567890001', 'sim_number' => '123456789012', 'no_kk' => '1234567890123456', 'nip_nim' => '123456789', 'pekerjaan' => 'Swasta', 'address' => 'Jl. Merdeka No 45, Jakarta', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 3. Model Has Roles
        DB::table('model_has_roles')->insert([
            ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => 1],
            ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['role_id' => 3, 'model_type' => 'App\\Models\\User', 'model_id' => 3],
            ['role_id' => 4, 'model_type' => 'App\\Models\\User', 'model_id' => 4],
            ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => 5],
            ['role_id' => 6, 'model_type' => 'App\\Models\\User', 'model_id' => 6],
            ['role_id' => 7, 'model_type' => 'App\\Models\\User', 'model_id' => 7],
        ]);

        // 4. Stores (formerly Branches)
        DB::table('stores')->insert([
            ['id' => 1, 'name' => 'Store Jakarta Pusat', 'slug' => 'store-jakarta-pusat', 'phone' => '021-1234567', 'email' => 'jkt@siliwangi.com', 'address' => 'Jl. Jend. Sudirman No. 1', 'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta', 'status' => 1, 'google_maps' => '-6.211548, 106.822989', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Store Bandung', 'slug' => 'store-bandung', 'phone' => '022-7654321', 'email' => 'bdg@siliwangi.com', 'address' => 'Jl. Asia Afrika No. 100', 'city' => 'Bandung', 'province' => 'Jawa Barat', 'status' => 1, 'google_maps' => '-6.921876, 107.611116', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 4.1 Employees
        if (\Illuminate\Support\Facades\Schema::hasTable('employees')) {
            DB::table('employees')->insert([
                [
                    'id' => 1,
                    'user_id' => 1,
                    'store_id' => 1,
                    'name' => 'Super Admin',
                    'email' => 'admin@siliwangi.com',
                    'phone' => '081200000001',
                    'nip' => 'NIP-00001',
                    'position' => 'Super Admin',
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'id' => 2,
                    'user_id' => 2,
                    'store_id' => 1,
                    'name' => 'Owner',
                    'email' => 'owner@siliwangi.com',
                    'phone' => '081200000002',
                    'nip' => 'NIP-00002',
                    'position' => 'Owner',
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'id' => 3,
                    'user_id' => 4,
                    'store_id' => 1,
                    'name' => 'Siti Finance',
                    'email' => 'finance@siliwangi.com',
                    'phone' => '081200000004',
                    'nip' => 'NIP-00004',
                    'position' => 'Finance',
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'id' => 4,
                    'user_id' => 5,
                    'store_id' => 1,
                    'name' => 'Asep Supir',
                    'email' => 'asep@siliwangi.com',
                    'phone' => '081200000005',
                    'nip' => 'NIP-00005',
                    'position' => 'Driver',
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'id' => 5,
                    'user_id' => 6,
                    'store_id' => 1,
                    'name' => 'Operasional',
                    'email' => 'operasional@siliwangi.com',
                    'phone' => '081200000006',
                    'nip' => 'NIP-00006',
                    'position' => 'Operasional',
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'id' => 6,
                    'user_id' => 7,
                    'store_id' => 1,
                    'name' => 'Staff Admin',
                    'email' => 'staff.admin@siliwangi.com',
                    'phone' => '081200000007',
                    'nip' => 'NIP-00007',
                    'position' => 'Staff Admin',
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }

        // 4.5 Car Brands
        if (\Illuminate\Support\Facades\Schema::hasTable('car_brands')) {
            DB::table('car_brands')->insert([
                ['id' => 1, 'name' => 'Toyota', 'slug' => 'toyota', 'logo' => null, 'created_at' => $now, 'updated_at' => $now],
                ['id' => 2, 'name' => 'Honda', 'slug' => 'honda', 'logo' => null, 'created_at' => $now, 'updated_at' => $now],
                ['id' => 3, 'name' => 'Mitsubishi', 'slug' => 'mitsubishi', 'logo' => null, 'created_at' => $now, 'updated_at' => $now],
                ['id' => 4, 'name' => 'Mercedes-Benz', 'slug' => 'mercedes-benz', 'logo' => null, 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // 4.6 Car Types
        if (\Illuminate\Support\Facades\Schema::hasTable('car_types')) {
            DB::table('car_types')->insert([
                ['id' => 1, 'name' => 'MPV', 'slug' => 'mpv', 'description' => 'Multi Purpose Family Vehicle', 'created_at' => $now, 'updated_at' => $now],
                ['id' => 2, 'name' => 'SUV', 'slug' => 'suv', 'description' => 'Sport Utility Vehicle', 'created_at' => $now, 'updated_at' => $now],
                ['id' => 3, 'name' => 'Luxury', 'slug' => 'luxury', 'description' => 'Premium luxury vehicles', 'created_at' => $now, 'updated_at' => $now],
                ['id' => 4, 'name' => 'City Car', 'slug' => 'city-car', 'description' => 'Compact City Car', 'created_at' => $now, 'updated_at' => $now],
                ['id' => 5, 'name' => 'Minibus', 'slug' => 'minibus', 'description' => 'Large passenger van for group travel', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // 5. Cars (Merged Brand, Type, GPS, Gallery, Maintenances, Inspections)
        $carsData = [
            // Luxury / Call
            [
                'id' => 1,
                'brand_name' => 'Mercedes-Benz',
                'brand_slug' => 'mercedes-benz',
                'type_name' => 'Luxury',
                'type_description' => 'Premium luxury sedans and limousines',
                'car_name' => 'Mercedes-Benz S-Class',
                'slug' => 'mercedes-s-class',
                'daily_price' => 0,
                'driver_daily_price' => 0,
                'is_call_for_price' => 1,
                'thumbnail' => 'cars/mercedes_s_class.png'
            ],
            [
                'id' => 2,
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'SUV',
                'type_description' => 'Sport Utility Vehicle',
                'car_name' => 'Land Cruiser VX-R',
                'slug' => 'land-cruiser-vxr',
                'daily_price' => 0,
                'driver_daily_price' => 0,
                'is_call_for_price' => 1,
                'thumbnail' => 'cars/land_cruiser_vxr.png'
            ],
            // MPV Luxury
            [
                'id' => 3,
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'Luxury',
                'type_description' => 'Premium luxury vehicles',
                'car_name' => 'Alphard New Generation',
                'slug' => 'alphard-new',
                'daily_price' => 3500000,
                'driver_daily_price' => 1000000,
                'is_call_for_price' => 0,
                'thumbnail' => 'cars/alphard_new.png'
            ],
            [
                'id' => 4,
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'Luxury',
                'type_description' => 'Premium luxury vehicles',
                'car_name' => 'Alphard Old Generation',
                'slug' => 'alphard-old',
                'daily_price' => 2500000,
                'driver_daily_price' => 1000000,
                'is_call_for_price' => 0,
                'thumbnail' => 'cars/alphard_old.png'
            ],
            // SUV
            [
                'id' => 5,
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'SUV',
                'type_description' => 'Sport Utility Vehicle',
                'car_name' => 'Toyota Fortuner GR',
                'slug' => 'fortuner-gr',
                'daily_price' => 1500000,
                'driver_daily_price' => 1000000,
                'is_call_for_price' => 0,
                'thumbnail' => 'cars/fortuner_gr.png'
            ],
            [
                'id' => 6,
                'brand_name' => 'Mitsubishi',
                'brand_slug' => 'mitsubishi',
                'type_name' => 'SUV',
                'type_description' => 'Sport Utility Vehicle',
                'car_name' => 'Mitsubishi Pajero Sport',
                'slug' => 'pajero-sport',
                'daily_price' => 1500000,
                'driver_daily_price' => 1000000,
                'is_call_for_price' => 0,
                'thumbnail' => 'cars/pajero_sport.png'
            ],
            [
                'id' => 7,
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'SUV',
                'type_description' => 'Sport Utility Vehicle',
                'car_name' => 'Toyota Rush',
                'slug' => 'toyota-rush',
                'daily_price' => 500000,
                'driver_daily_price' => 500000,
                'is_call_for_price' => 0,
                'thumbnail' => 'cars/toyota_rush.png'
            ],
            // MPV
            [
                'id' => 8,
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'MPV',
                'type_description' => 'Multi Purpose Family Vehicle',
                'car_name' => 'Toyota Innova Zenix Q',
                'slug' => 'innova-zenix-q',
                'daily_price' => 1300000,
                'driver_daily_price' => 1000000,
                'is_call_for_price' => 0,
                'thumbnail' => 'cars/innova_zenix.png'
            ],
            [
                'id' => 9,
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'MPV',
                'type_description' => 'Multi Purpose Family Vehicle',
                'car_name' => 'Toyota Innova G',
                'slug' => 'innova-g',
                'daily_price' => 800000,
                'driver_daily_price' => 600000,
                'is_call_for_price' => 0,
                'thumbnail' => 'cars/innova_g.png'
            ],
            [
                'id' => 10,
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'MPV',
                'type_description' => 'Multi Purpose Family Vehicle',
                'car_name' => 'Toyota Innova Reborn',
                'slug' => 'innova-reborn',
                'daily_price' => 700000,
                'driver_daily_price' => 600000,
                'is_call_for_price' => 0,
                'thumbnail' => 'cars/innova_reborn.png'
            ],
            [
                'id' => 11,
                'brand_name' => 'Mitsubishi',
                'brand_slug' => 'mitsubishi',
                'type_name' => 'MPV',
                'type_description' => 'Multi Purpose Family Vehicle',
                'car_name' => 'Mitsubishi Xpander',
                'slug' => 'mitsubishi-xpander',
                'daily_price' => 500000,
                'driver_daily_price' => 500000,
                'is_call_for_price' => 0,
                'thumbnail' => 'cars/xpander.png'
            ],
            [
                'id' => 12,
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'MPV',
                'type_description' => 'Multi Purpose Family Vehicle',
                'car_name' => 'Toyota Avanza Veloz',
                'slug' => 'avanza-veloz',
                'daily_price' => 500000,
                'driver_daily_price' => 500000,
                'is_call_for_price' => 0,
                'thumbnail' => 'cars/avanza_veloz.png'
            ],
            // City Car
            [
                'id' => 13,
                'brand_name' => 'Honda',
                'brand_slug' => 'honda',
                'type_name' => 'City Car',
                'type_description' => 'Compact City Car',
                'car_name' => 'Honda Brio RS',
                'slug' => 'honda-brio-rs',
                'daily_price' => 450000,
                'driver_daily_price' => 450000,
                'is_call_for_price' => 0,
                'thumbnail' => 'cars/honda_brio_rs.png'
            ],
            // Minibus
            [
                'id' => 14,
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'Minibus',
                'type_description' => 'Large passenger van for group travel',
                'car_name' => 'Toyota Hiace Premio Hitam',
                'slug' => 'toyota-hiace-premio-hitam',
                'daily_price' => 1200000,
                'driver_daily_price' => 800000,
                'is_call_for_price' => 0,
                'seat' => 15,
                'color' => 'Hitam',
                'thumbnail' => 'cars/toyota_hiace_premio_hitam.png'
            ],
            [
                'id' => 15,
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'Minibus',
                'type_description' => 'Large passenger van for group travel',
                'car_name' => 'Toyota Hiace Premio Putih',
                'slug' => 'toyota-hiace-premio-putih',
                'daily_price' => 1200000,
                'driver_daily_price' => 800000,
                'is_call_for_price' => 0,
                'seat' => 15,
                'color' => 'Putih',
                'thumbnail' => 'cars/toyota_hiace_premio_putih.png'
            ],
        ];

        foreach ($carsData as $c) {
            DB::table('cars')->insert([
                'id' => $c['id'],
                'store_id' => ($c['id'] % 2 == 0) ? 2 : 1,
                'car_name' => $c['car_name'],
                'slug' => $c['slug'],
                'plate_number' => 'D ' . rand(1000, 9999) . ' ' . strtoupper(\Illuminate\Support\Str::random(3)),
                'year' => rand(2021, 2024),
                'color' => $c['color'] ?? 'Hitam',
                'seat' => $c['seat'] ?? (($c['type_name'] == 'City Car') ? 5 : 7),
                'transmission' => 'Automatic',
                'fuel_type' => ($c['car_name'] == 'Toyota Fortuner GR' || $c['car_name'] == 'Mitsubishi Pajero Sport') ? 'Diesel' : 'Bensin',
                'daily_price' => $c['daily_price'],
                'driver_daily_price' => $c['driver_daily_price'],
                'is_call_for_price' => $c['is_call_for_price'],
                'category' => $c['is_call_for_price'] ? 'perusahaan' : 'both',
                'monthly_price' => $c['daily_price'] * 20,
                'late_fee' => $c['daily_price'] * 0.1,
                'status' => 'available',
                'is_available' => 1,
                'featured' => ($c['id'] <= 4) ? 1 : 0,
                'brand_name' => $c['brand_name'],
                'brand_slug' => $c['brand_slug'],
                'brand_logo' => null,
                'type_name' => $c['type_name'],
                'type_description' => $c['type_description'],
                'images' => json_encode([$c['thumbnail']]),
                'latitude' => null,
                'longitude' => null,
                'speed' => null,
                'location_address' => null,
                'location_raw_data' => null,
                'maintenances' => json_encode([]),
                'thumbnail' => $c['thumbnail'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 7. Customers (Merged into users)

        // 8. Drivers
        DB::table('drivers')->insert([
            ['id' => 1, 'user_id' => 5, 'store_id' => 1, 'name' => 'Asep Supir', 'phone' => '081200000005', 'license_number' => '123456789012', 'daily_fee' => 150000, 'rating' => 4.8, 'status' => 'active', 'is_available' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'user_id' => null, 'store_id' => 2, 'name' => 'Ujang Santoso', 'phone' => '081299998888', 'license_number' => '987654321098', 'daily_fee' => 150000, 'rating' => 4.5, 'status' => 'active', 'is_available' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 9. Promos
        DB::table('promos')->insert([
            ['id' => 1, 'code' => 'WELCOME20', 'title' => 'Diskon Pengguna Baru', 'discount_type' => 'percentage', 'discount_value' => 20.00, 'minimum_transaction' => 500000, 'start_date' => '2026-01-01', 'end_date' => '2026-12-31', 'quota' => 100, 'used' => 10, 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'code' => 'MUDIK50K', 'title' => 'Diskon Lebaran', 'discount_type' => 'fixed', 'discount_value' => 50000.00, 'minimum_transaction' => 300000, 'start_date' => '2026-03-01', 'end_date' => '2026-05-31', 'quota' => 50, 'used' => 5, 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 10. Bookings
        DB::table('bookings')->insert([
            ['id' => 1, 'booking_code' => 'TRX-202605-0001', 'user_id' => 3, 'car_id' => 1, 'driver_id' => null, 'store_id' => 1, 'promo_id' => null, 'rental_type' => 'daily', 'pickup_date' => Carbon::now()->subDays(2), 'return_date' => Carbon::now()->addDay(), 'pickup_location' => 'Kantor Pusat', 'return_location' => 'Kantor Pusat', 'total_day' => 3, 'price' => 1050000, 'driver_price' => 0, 'extra_price' => 0, 'late_fee' => 0, 'discount' => 0, 'tax' => 105000, 'grand_total' => 1155000, 'dp_amount' => 1155000, 'remaining_payment' => 0, 'payment_status' => 'paid', 'booking_status' => 'ongoing', 'notes' => 'Tolong cuci bersih sebelum pick up', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'booking_code' => 'TRX-202605-0002', 'user_id' => 3, 'car_id' => 2, 'driver_id' => 1, 'store_id' => 1, 'promo_id' => 1, 'rental_type' => 'daily', 'pickup_date' => Carbon::now()->addDays(5), 'return_date' => Carbon::now()->addDays(7), 'pickup_location' => 'Bandara Soekarno Hatta', 'return_location' => 'Kantor Pusat', 'total_day' => 2, 'price' => 1500000, 'driver_price' => 300000, 'extra_price' => 0, 'late_fee' => 0, 'discount' => 300000, 'tax' => 150000, 'grand_total' => 1650000, 'dp_amount' => 500000, 'remaining_payment' => 1150000, 'payment_status' => 'partial', 'booking_status' => 'confirmed', 'notes' => 'Jemput di Terminal 3', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 11. Payments
        DB::table('payments')->insert([
            ['id' => 1, 'booking_id' => 1, 'payment_code' => 'PAY-202605-0001', 'payment_method' => 'bank_transfer', 'transaction_id' => 'MID-123456789', 'gross_amount' => 1155000, 'paid_amount' => 1155000, 'payment_status' => 'success', 'payment_date' => $now, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'booking_id' => 2, 'payment_code' => 'PAY-202605-0002', 'payment_method' => 'qris', 'transaction_id' => 'MID-987654321', 'gross_amount' => 1650000, 'paid_amount' => 500000, 'payment_status' => 'success', 'payment_date' => $now, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 12. Reviews
        DB::table('reviews')->insert([
            ['id' => 1, 'booking_id' => 1, 'user_id' => 3, 'car_id' => 1, 'rating' => 5, 'review' => 'Mobil sangat bersih dan wangi, mesin juga prima!', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 13. Car Maintenances
        if (\Illuminate\Support\Facades\Schema::hasTable('car_maintenances')) {
            DB::table('car_maintenances')->insert([
                ['id' => 1, 'car_id' => 1, 'store_id' => 1, 'start_date' => '2026-05-10', 'end_date' => '2026-05-10', 'maintenance_type' => 'rutin', 'cost' => 350000.00, 'description' => 'Ganti Oli Mesin & Filter Oli', 'status' => 'completed', 'created_at' => $now, 'updated_at' => $now],
                ['id' => 2, 'car_id' => 2, 'store_id' => 1, 'start_date' => '2026-04-15', 'end_date' => '2026-04-15', 'maintenance_type' => 'perbaikan', 'cost' => 1200000.00, 'description' => 'Ganti Kampas Rem & Tune Up', 'status' => 'completed', 'created_at' => $now, 'updated_at' => $now],
                ['id' => 3, 'car_id' => 4, 'store_id' => 2, 'start_date' => '2026-05-14', 'end_date' => '2026-05-20', 'maintenance_type' => 'suspensi', 'cost' => 2500000.00, 'description' => 'Perbaikan Suspensi & Kaki-kaki', 'status' => 'ongoing', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // 14. Operationals (Vehicle Inspections)
        DB::table('operationals')->insert([
            [
                'id' => 1,
                'store_id' => 1,
                'booking_id' => 1,
                'car_id' => 1,
                'inspector_name' => 'Operasional Staff',
                'inspection_type' => 'pre_rental',
                'inspected_at' => $now,
                'odometer_km' => 12000,
                'fuel_level' => 'full',
                'exterior' => json_encode(['body' => 'Sangat Mulus', 'lights' => 'Semua Berfungsi']),
                'interior' => json_encode(['ac' => 'Dingin', 'seats' => 'Bersih']),
                'equipment' => json_encode(['stnk' => 'Ada', 'dongkrak' => 'Ada']),
                'engine' => json_encode(['engine_sound' => 'Halus', 'oil' => 'Cukup']),
                'photos' => json_encode([]),
                'fuel_photos' => json_encode([]),
                'damage_found' => false,
                'damage_description' => null,
                'damage_cost' => 0,
                'dirty_fine' => 0,
                'fuel_fine' => 0,
                'damage_photos' => json_encode([]),
                'customer_confirmed' => true,
                'customer_note' => 'Siap digunakan.',
                'notes' => 'Inspeksi awal sebelum serah terima berhasil dilakukan.',
                'status' => 'approved',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        // 15. Car Locations
        if (\Illuminate\Support\Facades\Schema::hasTable('car_locations')) {
            DB::table('car_locations')->insert([
                ['id' => 1, 'car_id' => 1, 'latitude' => -6.20880000, 'longitude' => 106.84560000, 'speed' => 0.00, 'address' => 'Pusat Jakarta', 'raw_data' => null, 'created_at' => $now, 'updated_at' => $now],
                ['id' => 2, 'car_id' => 2, 'latitude' => -6.17510000, 'longitude' => 106.86500000, 'speed' => 45.50, 'address' => 'Kecamatan Senen, Jakarta Pusat', 'raw_data' => null, 'created_at' => $now, 'updated_at' => $now],
                ['id' => 3, 'car_id' => 3, 'latitude' => -6.91750000, 'longitude' => 107.61910000, 'speed' => 0.00, 'address' => 'Jalan Asia Afrika, Bandung', 'raw_data' => null, 'created_at' => $now, 'updated_at' => $now],
                ['id' => 4, 'car_id' => 4, 'latitude' => -6.90340000, 'longitude' => 107.61870000, 'speed' => 0.00, 'address' => 'Perbengkelan Cabang Bandung', 'raw_data' => null, 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // 16. Expenses
        DB::table('expenses')->insert([
            ['id' => 1, 'date' => $now->copy()->subDays(5)->format('Y-m-d'), 'category' => 'fuel', 'store_id' => 1, 'amount' => 450000.00, 'description' => 'Isi Pertamax Avanza', 'attachment' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'date' => $now->copy()->subDays(10)->format('Y-m-d'), 'category' => 'operational', 'store_id' => 1, 'amount' => 150000.00, 'description' => 'Cuci Mobil & Salon', 'attachment' => null, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'date' => $now->copy()->subDays(15)->format('Y-m-d'), 'category' => 'marketing', 'store_id' => 2, 'amount' => 1000000.00, 'description' => 'Instagram Ads', 'attachment' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 17. Location Surveys
        if (\Illuminate\Support\Facades\Schema::hasTable('location_surveys')) {
            DB::table('location_surveys')->insert([
                [
                    'id' => 1,
                    'store_id' => 1,
                    'booking_id' => 1,
                    'surveyor_name' => 'Budi Surveyor',
                    'survey_date' => $now->copy()->subDays(3)->format('Y-m-d'),
                    'survey_type' => 'delivery',
                    'address' => 'Jl. Merdeka No. 45, Jakarta Pusat',
                    'residence_status' => json_encode(['status' => 'Milik Sendiri', 'proof' => 'PBB & Rekening Listrik']),
                    'job_status' => json_encode(['occupation' => 'Karyawan Swasta', 'company' => 'PT Maju Mundur']),
                    'neighbor_interview' => json_encode(['rt_rw_verification' => 'Terverifikasi benar tinggal di alamat tersebut', 'character' => 'Baik dan bersosialisasi']),
                    'photos' => json_encode([]),
                    'recommendation' => 'layak',
                    'notes' => 'Lokasi terverifikasi, tetangga mengonfirmasi kustomer bertempat tinggal di alamat tersebut selama 5 tahun.',
                    'status' => 'approved',
                    'approved_by' => 1, // Super Admin
                    'approved_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            ]);
        }

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }
}
