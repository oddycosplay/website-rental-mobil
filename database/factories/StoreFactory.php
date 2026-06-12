<?php

namespace Database\Factories;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Store>
 */
class StoreFactory extends Factory
{
    protected $model = Store::class;

    public function definition(): array
    {
        $name = fake()->unique()->company() . ' Store';
        return [
            'name'    => $name,
            'slug'    => \Illuminate\Support\Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 999),
            'phone'   => fake()->numerify('0812#######'),
            'email'   => fake()->unique()->companyEmail(),
            'address' => fake()->streetAddress(),
            'city'    => fake()->city(),
            'province'=> fake()->state(),
            'status'  => 1,
        ];
    }
}
