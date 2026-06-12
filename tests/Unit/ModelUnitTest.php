<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Payment;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests for Siliwangi Rental Models
 * Covers: relationships, accessors, mutators, scopes, and business logic.
 */
class ModelUnitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    // ─────────────────────────────────────────────
    // User Model
    // ─────────────────────────────────────────────

    /** @test */
    public function test_user_has_customer_relationship(): void
    {
        $user     = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->customer()->exists());
        $this->assertEquals($customer->id, $user->customer->id);
    }

    /** @test */
    public function test_user_has_bookings_through_customer(): void
    {
        $store    = Store::factory()->create();
        $user     = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $car      = Car::factory()->create(['store_id' => $store->id]);

        Booking::factory()->create([
            'customer_id' => $customer->id,
            'car_id'      => $car->id,
            'store_id'    => $store->id,
        ]);

        $this->assertEquals(1, $user->bookings()->count());
    }

    /** @test */
    public function test_user_has_store_relationship(): void
    {
        $store = Store::factory()->create();
        $user  = User::factory()->create(['store_id' => $store->id]);

        $this->assertNotNull($user->store);
        $this->assertEquals($store->id, $user->store->id);
    }

    /** @test */
    public function test_user_branch_accessor_maps_to_store(): void
    {
        $store = Store::factory()->create();
        $user  = User::factory()->create(['store_id' => $store->id]);

        // BACKWARD COMPATIBILITY: branch_id should equal store_id
        $this->assertEquals($store->id, $user->branch_id);
    }

    /** @test */
    public function test_user_can_have_role_assigned(): void
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        $this->assertTrue($user->hasRole('customer'));
        $this->assertFalse($user->hasRole('super-admin'));
    }

    // ─────────────────────────────────────────────
    // Car Model
    // ─────────────────────────────────────────────

    /** @test */
    public function test_car_belongs_to_store(): void
    {
        $store = Store::factory()->create();
        $car   = Car::factory()->create(['store_id' => $store->id]);

        $this->assertNotNull($car->store);
        $this->assertEquals($store->id, $car->store->id);
    }

    /** @test */
    public function test_car_brand_accessor_returns_object(): void
    {
        $car = Car::factory()->create([
            'store_id'   => Store::factory()->create()->id,
            'brand_name' => 'Toyota',
            'brand_slug' => 'toyota',
        ]);

        $brand = $car->brand;
        $this->assertEquals('Toyota', $brand->name);
        $this->assertEquals('toyota', $brand->slug);
    }

    /** @test */
    public function test_car_type_accessor_returns_object(): void
    {
        $car = Car::factory()->create([
            'store_id'   => Store::factory()->create()->id,
            'type_name'  => 'MPV',
            'type_description' => 'Multi Purpose Vehicle',
        ]);

        $type = $car->type;
        $this->assertEquals('MPV', $type->name);
        $this->assertEquals('Multi Purpose Vehicle', $type->description);
    }

    /** @test */
    public function test_car_stock_accessor_always_returns_one(): void
    {
        $car = Car::factory()->create(['store_id' => Store::factory()->create()->id]);

        $this->assertEquals(1, $car->stock);
    }

    /** @test */
    public function test_car_branch_id_maps_to_store_id(): void
    {
        $store = Store::factory()->create();
        $car   = Car::factory()->create(['store_id' => $store->id]);

        $this->assertEquals($store->id, $car->branch_id);
    }

    // ─────────────────────────────────────────────
    // Store Model
    // ─────────────────────────────────────────────

    /** @test */
    public function test_store_has_many_cars(): void
    {
        $store = Store::factory()->create();
        Car::factory()->count(3)->create(['store_id' => $store->id]);

        $this->assertCount(3, $store->cars);
    }

    /** @test */
    public function test_store_has_many_bookings(): void
    {
        $store    = Store::factory()->create();
        $customer = Customer::factory()->create();
        $car      = Car::factory()->create(['store_id' => $store->id]);

        Booking::factory()->count(2)->create([
            'store_id'    => $store->id,
            'customer_id' => $customer->id,
            'car_id'      => $car->id,
        ]);

        $this->assertCount(2, $store->bookings);
    }

    // ─────────────────────────────────────────────
    // Customer Model
    // ─────────────────────────────────────────────

    /** @test */
    public function test_customer_belongs_to_user(): void
    {
        $user     = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);

        $this->assertNotNull($customer->user);
        $this->assertEquals($user->id, $customer->user->id);
    }

    /** @test */
    public function test_customer_has_many_bookings(): void
    {
        $store    = Store::factory()->create();
        $customer = Customer::factory()->create();
        $car      = Car::factory()->create(['store_id' => $store->id]);

        Booking::factory()->count(3)->create([
            'customer_id' => $customer->id,
            'car_id'      => $car->id,
            'store_id'    => $store->id,
        ]);

        $this->assertCount(3, $customer->bookings);
    }

    // ─────────────────────────────────────────────
    // Booking Model
    // ─────────────────────────────────────────────

    /** @test */
    public function test_booking_has_correct_relationships(): void
    {
        $store    = Store::factory()->create();
        $customer = Customer::factory()->create();
        $car      = Car::factory()->create(['store_id' => $store->id]);

        $booking = Booking::factory()->create([
            'customer_id' => $customer->id,
            'car_id'      => $car->id,
            'store_id'    => $store->id,
        ]);

        $this->assertEquals($customer->id, $booking->customer->id);
        $this->assertEquals($car->id, $booking->car->id);
        $this->assertEquals($store->id, $booking->store->id);
    }

    /** @test */
    public function test_booking_has_one_payment(): void
    {
        $store    = Store::factory()->create();
        $customer = Customer::factory()->create();
        $car      = Car::factory()->create(['store_id' => $store->id]);

        $booking = Booking::factory()->create([
            'customer_id' => $customer->id,
            'car_id'      => $car->id,
            'store_id'    => $store->id,
        ]);

        Payment::factory()->create([
            'booking_id'  => $booking->id,
            'gross_amount'=> $booking->grand_total,
        ]);

        $this->assertNotNull($booking->payment);
        $this->assertEquals($booking->id, $booking->payment->booking_id);
    }

    /** @test */
    public function test_booking_branch_accessor_maps_to_store(): void
    {
        $store    = Store::factory()->create();
        $customer = Customer::factory()->create();
        $car      = Car::factory()->create(['store_id' => $store->id]);

        $booking = Booking::factory()->create([
            'customer_id' => $customer->id,
            'car_id'      => $car->id,
            'store_id'    => $store->id,
        ]);

        // BACKWARD COMPATIBILITY: branch_id should equal store_id
        $this->assertEquals($store->id, $booking->branch_id);
    }

    /** @test */
    public function test_booking_rental_type_alias_accessors(): void
    {
        $store    = Store::factory()->create();
        $customer = Customer::factory()->create();
        $car      = Car::factory()->create(['store_id' => $store->id]);

        $booking = Booking::factory()->create([
            'customer_id' => $customer->id,
            'car_id'      => $car->id,
            'store_id'    => $store->id,
            'rental_type' => 'daily',
        ]);

        // BACKWARD COMPATIBILITY
        $this->assertEquals('daily', $booking->booking_type);
        $this->assertEquals('pribadi', $booking->booking_category);
    }

    // ─────────────────────────────────────────────
    // Payment Model
    // ─────────────────────────────────────────────

    /** @test */
    public function test_payment_belongs_to_booking(): void
    {
        $store    = Store::factory()->create();
        $customer = Customer::factory()->create();
        $car      = Car::factory()->create(['store_id' => $store->id]);

        $booking = Booking::factory()->create([
            'customer_id' => $customer->id,
            'car_id'      => $car->id,
            'store_id'    => $store->id,
        ]);

        $payment = Payment::factory()->create(['booking_id' => $booking->id]);

        $this->assertEquals($booking->id, $payment->booking->id);
    }

    /** @test */
    public function test_payment_logs_virtual_attribute_returns_collection(): void
    {
        $store    = Store::factory()->create();
        $customer = Customer::factory()->create();
        $car      = Car::factory()->create(['store_id' => $store->id]);

        $booking = Booking::factory()->create([
            'customer_id' => $customer->id,
            'car_id'      => $car->id,
            'store_id'    => $store->id,
        ]);

        // payment_logs is cast to array, so we must pass a PHP array
        $payment = Payment::factory()->create([
            'booking_id'   => $booking->id,
            'payment_logs' => [
                ['status' => 'pending', 'response' => [], 'created_at' => now()->toIso8601String()],
            ],
        ]);

        $payment->refresh();

        $logs = $payment->logs;
        $this->assertCount(1, $logs);
        $this->assertEquals('pending', $logs->first()->status);
    }
}
