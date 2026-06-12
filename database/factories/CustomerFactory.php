<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'user_id'         => User::factory(),
            'name'            => fake()->name(),
            'email'           => fake()->unique()->safeEmail(),
            'phone'           => fake()->numerify('0812#######'),
            'nik'             => fake()->numerify('3273################'),
            'sim_number'      => fake()->numerify('############'),
            'address'         => fake()->address(),
            'customer_status' => 'approved',
            'is_active'       => true,
        ];
    }
}
