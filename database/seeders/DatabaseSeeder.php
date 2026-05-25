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
        DB::table('users')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('customers')->truncate();
        DB::table('stores')->truncate();
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
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 1. Roles
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'super-admin', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'owner', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'customer', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'finance', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'driver', 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 2. Users
        $password = Hash::make('password'); // Default password
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Super Admin', 'email' => 'admin@siliwangi.com', 'phone' => '081200000001', 'password' => $password, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Owner', 'email' => 'owner@siliwangi.com', 'phone' => '081200000002', 'password' => $password, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'name' => 'Budi Customer', 'email' => 'budi@gmail.com', 'phone' => '081200000003', 'password' => $password, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'name' => 'Siti Finance', 'email' => 'finance@siliwangi.com', 'phone' => '081200000004', 'password' => $password, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'name' => 'Asep Supir', 'email' => 'asep@siliwangi.com', 'phone' => '081200000005', 'password' => $password, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 3. Model Has Roles
        DB::table('model_has_roles')->insert([
            ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => 1],
            ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => 2],
            ['role_id' => 3, 'model_type' => 'App\\Models\\User', 'model_id' => 3],
            ['role_id' => 4, 'model_type' => 'App\\Models\\User', 'model_id' => 4],
            ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => 5],
        ]);

        // 3.5 Customers
        DB::table('customers')->insert([
            [
                'id' => 1,
                'user_id' => 3,
                'name' => 'Budi Customer',
                'email' => 'budi@gmail.com',
                'phone' => '081200000003',
                'nik' => '3171234567890001',
                'sim_number' => '123456789012',
                'customer_status' => 'approved',
                'address' => 'Jl. Merdeka No 45, Jakarta',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ]);

        // 4. Stores (formerly Branches)
        DB::table('stores')->insert([
            ['id' => 1, 'name' => 'Store Jakarta Pusat', 'slug' => 'store-jakarta-pusat', 'phone' => '021-1234567', 'email' => 'jkt@siliwangi.com', 'address' => 'Jl. Jend. Sudirman No. 1', 'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => 'Store Bandung', 'slug' => 'store-bandung', 'phone' => '022-7654321', 'email' => 'bdg@siliwangi.com', 'address' => 'Jl. Asia Afrika No. 100', 'city' => 'Bandung', 'province' => 'Jawa Barat', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 4.5 Car Brands
        if (\Illuminate\Support\Facades\Schema::hasTable('car_brands')) {
            DB::table('car_brands')->insert([
                ['id' => 1, 'name' => 'Toyota', 'slug' => 'toyota', 'logo' => null, 'created_at' => $now, 'updated_at' => $now],
                ['id' => 2, 'name' => 'Honda', 'slug' => 'honda', 'logo' => null, 'created_at' => $now, 'updated_at' => $now],
                ['id' => 3, 'name' => 'Mitsubishi', 'slug' => 'mitsubishi', 'logo' => null, 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // 4.6 Car Types
        if (\Illuminate\Support\Facades\Schema::hasTable('car_types')) {
            DB::table('car_types')->insert([
                ['id' => 1, 'name' => 'MPV', 'slug' => 'mpv', 'description' => 'Multi Purpose Family Vehicle', 'created_at' => $now, 'updated_at' => $now],
                ['id' => 2, 'name' => 'SUV', 'slug' => 'suv', 'description' => 'Sport Utility Vehicle', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // 5. Cars (Merged Brand, Type, GPS, Gallery, Maintenances, Inspections)
        DB::table('cars')->insert([
            [
                'id' => 1,
                'store_id' => 1,
                'car_name' => 'Toyota Avanza Veloz',
                'slug' => 'toyota-avanza-veloz',
                'plate_number' => 'B 1234 ABC',
                'year' => 2023,
                'color' => 'Putih',
                'seat' => 7,
                'transmission' => 'Automatic',
                'fuel_type' => 'Bensin',
                'daily_price' => 350000,
                'monthly_price' => 7000000,
                'late_fee' => 35000,
                'status' => 'available',
                'is_available' => 1,
                'featured' => 1,
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'brand_logo' => null,
                'type_name' => 'MPV',
                'type_description' => 'Multi Purpose Family Vehicle',
                'images' => json_encode(['cars/gallery/veloz-1.webp', 'cars/gallery/veloz-2.webp']),
                'latitude' => -6.20880000,
                'longitude' => 106.84560000,
                'speed' => 0.00,
                'location_address' => 'Pusat Jakarta',
                'location_raw_data' => null,
                'maintenances' => json_encode([
                    ['maintenance_date' => '2026-05-10', 'cost' => 350000, 'description' => 'Ganti Oli Mesin & Filter Oli']
                ]),
                'inspections' => json_encode([
                    ['inspection_date' => '2026-05-18', 'inspector' => 'Ahmad', 'condition' => 'excellent', 'is_clean' => true]
                ]),
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 2,
                'store_id' => 1,
                'car_name' => 'Honda CR-V',
                'slug' => 'honda-cr-v',
                'plate_number' => 'B 5678 DEF',
                'year' => 2022,
                'color' => 'Hitam',
                'seat' => 5,
                'transmission' => 'Automatic',
                'fuel_type' => 'Bensin',
                'daily_price' => 750000,
                'monthly_price' => 15000000,
                'late_fee' => 75000,
                'status' => 'rented',
                'is_available' => 0,
                'featured' => 1,
                'brand_name' => 'Honda',
                'brand_slug' => 'honda',
                'brand_logo' => null,
                'type_name' => 'SUV',
                'type_description' => 'Sport Utility Vehicle',
                'images' => json_encode(['cars/gallery/crv-1.webp']),
                'latitude' => -6.17510000,
                'longitude' => 106.86500000,
                'speed' => 45.50,
                'location_address' => 'Kecamatan Senen, Jakarta Pusat',
                'location_raw_data' => null,
                'maintenances' => json_encode([
                    ['maintenance_date' => '2026-04-15', 'cost' => 1200000, 'description' => 'Ganti Kampas Rem & Tune Up']
                ]),
                'inspections' => json_encode([
                    ['inspection_date' => '2026-05-12', 'inspector' => 'Rian', 'condition' => 'good', 'is_clean' => true]
                ]),
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 3,
                'store_id' => 2,
                'car_name' => 'Mitsubishi Xpander',
                'slug' => 'mitsubishi-xpander',
                'plate_number' => 'D 9012 GHI',
                'year' => 2023,
                'color' => 'Silver',
                'seat' => 7,
                'transmission' => 'Automatic',
                'fuel_type' => 'Bensin',
                'daily_price' => 400000,
                'monthly_price' => 8000000,
                'late_fee' => 40000,
                'status' => 'available',
                'is_available' => 1,
                'featured' => 0,
                'brand_name' => 'Mitsubishi',
                'brand_slug' => 'mitsubishi',
                'brand_logo' => null,
                'type_name' => 'MPV',
                'type_description' => 'Comfortable Crossover MPV',
                'images' => json_encode([]),
                'latitude' => -6.91750000,
                'longitude' => 107.61910000,
                'speed' => 0.00,
                'location_address' => 'Jalan Asia Afrika, Bandung',
                'location_raw_data' => null,
                'maintenances' => json_encode([]),
                'inspections' => json_encode([
                    ['inspection_date' => '2026-05-17', 'inspector' => 'Dedi', 'condition' => 'excellent', 'is_clean' => true]
                ]),
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 4,
                'store_id' => 2,
                'car_name' => 'Toyota Fortuner GR',
                'slug' => 'toyota-fortuner-gr',
                'plate_number' => 'D 3456 JKL',
                'year' => 2024,
                'color' => 'Hitam',
                'seat' => 7,
                'transmission' => 'Automatic',
                'fuel_type' => 'Diesel',
                'daily_price' => 1200000,
                'monthly_price' => 24000000,
                'late_fee' => 120000,
                'status' => 'maintenance',
                'is_available' => 0,
                'featured' => 1,
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'brand_logo' => null,
                'type_name' => 'SUV',
                'type_description' => 'Premium Heavy-Duty SUV',
                'images' => json_encode([]),
                'latitude' => -6.90340000,
                'longitude' => 107.61870000,
                'speed' => 0.00,
                'location_address' => 'Perbengkelan Cabang Bandung',
                'location_raw_data' => null,
                'maintenances' => json_encode([
                    ['maintenance_date' => '2026-05-14', 'cost' => 2500000, 'description' => 'Perbaikan Suspensi & Kaki-kaki']
                ]),
                'inspections' => json_encode([
                    ['inspection_date' => '2026-05-14', 'inspector' => 'Dedi', 'condition' => 'fair', 'is_clean' => false]
                ]),
                'created_at' => $now,
                'updated_at' => $now
            ],
        ]);

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
            ['id' => 1, 'booking_code' => 'TRX-202605-0001', 'customer_id' => 1, 'car_id' => 1, 'driver_id' => null, 'store_id' => 1, 'promo_id' => null, 'rental_type' => 'daily', 'pickup_date' => Carbon::now()->subDays(2), 'return_date' => Carbon::now()->addDay(), 'pickup_location' => 'Kantor Pusat', 'return_location' => 'Kantor Pusat', 'total_day' => 3, 'price' => 1050000, 'driver_price' => 0, 'extra_price' => 0, 'late_fee' => 0, 'discount' => 0, 'tax' => 105000, 'grand_total' => 1155000, 'dp_amount' => 1155000, 'remaining_payment' => 0, 'payment_status' => 'paid', 'booking_status' => 'ongoing', 'notes' => 'Tolong cuci bersih sebelum pick up', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'booking_code' => 'TRX-202605-0002', 'customer_id' => 1, 'car_id' => 2, 'driver_id' => 1, 'store_id' => 1, 'promo_id' => 1, 'rental_type' => 'daily', 'pickup_date' => Carbon::now()->addDays(5), 'return_date' => Carbon::now()->addDays(7), 'pickup_location' => 'Bandara Soekarno Hatta', 'return_location' => 'Kantor Pusat', 'total_day' => 2, 'price' => 1500000, 'driver_price' => 300000, 'extra_price' => 0, 'late_fee' => 0, 'discount' => 300000, 'tax' => 150000, 'grand_total' => 1650000, 'dp_amount' => 500000, 'remaining_payment' => 1150000, 'payment_status' => 'partial', 'booking_status' => 'confirmed', 'notes' => 'Jemput di Terminal 3', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 11. Payments
        DB::table('payments')->insert([
            ['id' => 1, 'booking_id' => 1, 'payment_code' => 'PAY-202605-0001', 'payment_method' => 'bank_transfer', 'transaction_id' => 'MID-123456789', 'gross_amount' => 1155000, 'paid_amount' => 1155000, 'payment_status' => 'success', 'payment_date' => $now, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'booking_id' => 2, 'payment_code' => 'PAY-202605-0002', 'payment_method' => 'qris', 'transaction_id' => 'MID-987654321', 'gross_amount' => 1650000, 'paid_amount' => 500000, 'payment_status' => 'success', 'payment_date' => $now, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 12. Reviews
        DB::table('reviews')->insert([
            ['id' => 1, 'booking_id' => 1, 'customer_id' => 1, 'car_id' => 1, 'rating' => 5, 'review' => 'Mobil sangat bersih dan wangi, mesin juga prima!', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 13. Car Maintenances
        if (\Illuminate\Support\Facades\Schema::hasTable('car_maintenances')) {
            DB::table('car_maintenances')->insert([
                ['id' => 1, 'car_id' => 1, 'store_id' => 1, 'start_date' => '2026-05-10', 'end_date' => '2026-05-10', 'maintenance_type' => 'rutin', 'cost' => 350000.00, 'description' => 'Ganti Oli Mesin & Filter Oli', 'status' => 'completed', 'created_at' => $now, 'updated_at' => $now],
                ['id' => 2, 'car_id' => 2, 'store_id' => 1, 'start_date' => '2026-04-15', 'end_date' => '2026-04-15', 'maintenance_type' => 'perbaikan', 'cost' => 1200000.00, 'description' => 'Ganti Kampas Rem & Tune Up', 'status' => 'completed', 'created_at' => $now, 'updated_at' => $now],
                ['id' => 3, 'car_id' => 4, 'store_id' => 2, 'start_date' => '2026-05-14', 'end_date' => '2026-05-20', 'maintenance_type' => 'suspensi', 'cost' => 2500000.00, 'description' => 'Perbaikan Suspensi & Kaki-kaki', 'status' => 'ongoing', 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

        // 14. Car Inspections
        if (\Illuminate\Support\Facades\Schema::hasTable('car_inspections')) {
            DB::table('car_inspections')->insert([
                ['id' => 1, 'booking_id' => 1, 'type' => 'return', 'mileage' => 15000, 'fuel_level' => 'full', 'checklist' => json_encode(['body' => 'ok', 'engine' => 'ok', 'interior' => 'ok']), 'notes' => 'Mobil dalam kondisi sangat baik.', 'inspector_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ]);
        }

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
    }
}
