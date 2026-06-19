<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Car>
 */
class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        $carName = fake()->randomElement(['Toyota Avanza', 'Honda Brio', 'Toyota Innova', 'Mitsubishi Xpander', 'Toyota Fortuner']);
        $suffix  = fake()->unique()->numberBetween(100, 9999);

        return [
            'store_id'          => Store::factory(),
            'car_name'          => $carName,
            'slug'              => Str::slug($carName) . '-' . $suffix,
            'plate_number'      => 'D ' . fake()->numerify('####') . ' ' . strtoupper(Str::random(3)),
            'year'              => fake()->numberBetween(2018, 2024),
            'color'             => fake()->colorName(),
            'seat'              => fake()->randomElement([5, 7, 8]),
            'transmission'      => 'Automatic',
            'fuel_type'         => fake()->randomElement(['Bensin', 'Diesel']),
            'daily_price'       => fake()->randomElement([500000, 700000, 1000000, 1500000]),
            'driver_daily_price'=> fake()->randomElement([150000, 200000, 300000]),
            'monthly_price'     => 10000000,
            'late_fee'          => 50000,
            'is_available'      => true,
            'featured'          => false,
            'status'            => 'available',
            'brand_name'        => fake()->randomElement(['Toyota', 'Honda', 'Mitsubishi']),
            'brand_slug'        => 'toyota',
            'type_name'         => fake()->randomElement(['MPV', 'SUV', 'City Car']),
        ];
    }
}
