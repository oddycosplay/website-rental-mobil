<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
 
class NewCarSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
 
        // Clear existing cars data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cars')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
 
        $cars = [
            // Luxury / Call
            [
                'brand_name' => 'Mercedes-Benz',
                'brand_slug' => 'mercedes-benz',
                'type_name' => 'Luxury',
                'type_description' => 'Premium luxury sedans and limousines',
                'name' => 'Mercedes-Benz S-Class',
                'slug' => 'mercedes-s-class',
                'daily' => 0,
                'driver' => 0,
                'call' => true,
                'thumbnail' => 'cars/mercedes_s_class.png'
            ],
            [
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'SUV',
                'type_description' => 'Sport Utility Vehicle',
                'name' => 'Land Cruiser VX-R',
                'slug' => 'land-cruiser-vxr',
                'daily' => 0,
                'driver' => 0,
                'call' => true,
                'thumbnail' => 'cars/land_cruiser_vxr.png'
            ],
 
            // MPV Luxury
            [
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'Luxury',
                'type_description' => 'Premium luxury vehicles',
                'name' => 'Alphard New Generation',
                'slug' => 'alphard-new',
                'daily' => 3500000,
                'driver' => 1000000,
                'call' => false,
                'thumbnail' => 'cars/alphard_new.png'
            ],
            [
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'Luxury',
                'type_description' => 'Premium luxury vehicles',
                'name' => 'Alphard Old Generation',
                'slug' => 'alphard-old',
                'daily' => 2500000,
                'driver' => 1000000,
                'call' => false,
                'thumbnail' => 'cars/alphard_old.png'
            ],
 
            // SUV
            [
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'SUV',
                'type_description' => 'Sport Utility Vehicle',
                'name' => 'Toyota Fortuner GR',
                'slug' => 'fortuner-gr',
                'daily' => 1500000,
                'driver' => 1000000,
                'call' => false,
                'thumbnail' => 'cars/fortuner_gr.png'
            ],
            [
                'brand_name' => 'Mitsubishi',
                'brand_slug' => 'mitsubishi',
                'type_name' => 'SUV',
                'type_description' => 'Sport Utility Vehicle',
                'name' => 'Mitsubishi Pajero Sport',
                'slug' => 'pajero-sport',
                'daily' => 1500000,
                'driver' => 1000000,
                'call' => false,
                'thumbnail' => 'cars/pajero_sport.png'
            ],
            [
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'SUV',
                'type_description' => 'Sport Utility Vehicle',
                'name' => 'Toyota Rush',
                'slug' => 'toyota-rush',
                'daily' => 500000,
                'driver' => 500000,
                'call' => false,
                'thumbnail' => 'cars/toyota_rush.png'
            ],
 
            // MPV
            [
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'MPV',
                'type_description' => 'Multi Purpose Family Vehicle',
                'name' => 'Toyota Innova Zenix Q',
                'slug' => 'innova-zenix-q',
                'daily' => 1300000,
                'driver' => 1000000,
                'call' => false,
                'thumbnail' => 'cars/innova_zenix.png'
            ],
            [
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'MPV',
                'type_description' => 'Multi Purpose Family Vehicle',
                'name' => 'Toyota Innova G',
                'slug' => 'innova-g',
                'daily' => 800000,
                'driver' => 600000,
                'call' => false,
                'thumbnail' => 'cars/innova_g.png'
            ],
            [
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'MPV',
                'type_description' => 'Multi Purpose Family Vehicle',
                'name' => 'Toyota Innova Reborn',
                'slug' => 'innova-reborn',
                'daily' => 700000,
                'driver' => 600000,
                'call' => false,
                'thumbnail' => 'cars/innova_reborn.png'
            ],
            [
                'brand_name' => 'Mitsubishi',
                'brand_slug' => 'mitsubishi',
                'type_name' => 'MPV',
                'type_description' => 'Multi Purpose Family Vehicle',
                'name' => 'Mitsubishi Xpander',
                'slug' => 'mitsubishi-xpander',
                'daily' => 500000,
                'driver' => 500000,
                'call' => false,
                'thumbnail' => 'cars/xpander.png'
            ],
            [
                'brand_name' => 'Toyota',
                'brand_slug' => 'toyota',
                'type_name' => 'MPV',
                'type_description' => 'Multi Purpose Family Vehicle',
                'name' => 'Toyota Avanza Veloz',
                'slug' => 'avanza-veloz',
                'daily' => 500000,
                'driver' => 500000,
                'call' => false,
                'thumbnail' => 'cars/avanza_veloz.png'
            ],
 
            // City Car
            [
                'brand_name' => 'Honda',
                'brand_slug' => 'honda',
                'type_name' => 'City Car',
                'type_description' => 'Compact City Car',
                'name' => 'Honda Brio RS',
                'slug' => 'honda-brio-rs',
                'daily' => 450000,
                'driver' => 450000,
                'call' => false,
                'thumbnail' => 'cars/honda_brio_rs.png'
            ],
        ];
 
        foreach ($cars as $car) {
            DB::table('cars')->insert([
                'store_id' => 1,
                'car_name' => $car['name'],
                'slug' => $car['slug'],
                'plate_number' => strtoupper(Str::random(1)) . ' ' . rand(1000, 9999) . ' ' . strtoupper(Str::random(3)),
                'year' => rand(2021, 2024),
                'color' => 'Hitam',
                'seat' => ($car['type_name'] == 'City Car') ? 5 : 7,
                'transmission' => 'Automatic',
                'fuel_type' => 'Bensin',
                'daily_price' => $car['daily'],
                'driver_daily_price' => $car['driver'],
                'is_call_for_price' => $car['call'],
                'monthly_price' => $car['daily'] * 20,
                'late_fee' => $car['daily'] * 0.1,
                'thumbnail' => $car['thumbnail'],
                'status' => 'available',
                'is_available' => 1,
                'featured' => rand(0, 1),
                'brand_name' => $car['brand_name'],
                'brand_slug' => $car['brand_slug'],
                'brand_logo' => null,
                'type_name' => $car['type_name'],
                'type_description' => $car['type_description'],
                'images' => json_encode([$car['thumbnail']]),
                'latitude' => null,
                'longitude' => null,
                'speed' => null,
                'location_address' => null,
                'location_raw_data' => null,
                'maintenances' => json_encode([]),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
