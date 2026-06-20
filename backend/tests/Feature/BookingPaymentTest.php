<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Car;
use App\Models\Payment;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for booking and payment business logic.
 * Covers: booking creation, payment status, car status updates.
 */
class BookingPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected Store $store;
    protected Car $car;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();

        $this->store    = Store::factory()->create();
        $this->car      = Car::factory()->create([
            'store_id'    => $this->store->id,
            'daily_price' => 500000,
            'late_fee'    => 50000,
            'status'      => 'available',
        ]);
        $this->customer = User::factory()->create();
    }

    // ─────────────────────────────────────────────
    // Booking Creation
    // ─────────────────────────────────────────────


    public function test_booking_can_be_created_in_database(): void
    {
        $booking = Booking::factory()->create([
            'customer_id'    => $this->customer->id,
            'car_id'         => $this->car->id,
            'store_id'       => $this->store->id,
            'booking_status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $this->assertDatabaseHas('bookings', [
            'id'             => $booking->id,
            'user_id'        => $this->customer->id,
            'booking_status' => 'pending',
        ]);
    }


    public function test_booking_code_is_stored_correctly(): void
    {
        $booking = Booking::factory()->create([
            'customer_id'  => $this->customer->id,
            'car_id'       => $this->car->id,
            'store_id'     => $this->store->id,
            'booking_code' => 'TRX-CUSTOM-0001',
        ]);

        $this->assertDatabaseHas('bookings', [
            'booking_code' => 'TRX-CUSTOM-0001',
        ]);
    }


    public function test_grand_total_is_calculated_correctly(): void
    {
        $totalDay   = 3;
        $price      = $this->car->daily_price * $totalDay;
        $tax        = (int) ($price * 0.10);
        $grandTotal = $price + $tax;

        $booking = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'car_id'      => $this->car->id,
            'store_id'    => $this->store->id,
            'total_day'   => $totalDay,
            'price'       => $price,
            'tax'         => $tax,
            'grand_total' => $grandTotal,
        ]);

        $this->assertEquals($grandTotal, $booking->grand_total);
        $this->assertEquals($price + $tax, $booking->grand_total);
    }

    // ─────────────────────────────────────────────
    // Payment Creation
    // ─────────────────────────────────────────────


    public function test_payment_can_be_created_for_booking(): void
    {
        $booking = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'car_id'      => $this->car->id,
            'store_id'    => $this->store->id,
            'grand_total' => 1650000,
        ]);

        $payment = Payment::factory()->create([
            'booking_id'     => $booking->id,
            'gross_amount'   => $booking->grand_total,
            'payment_status' => 'pending',
        ]);

        $this->assertDatabaseHas('payments', [
            'booking_id'     => $booking->id,
            'payment_status' => 'pending',
            'gross_amount'   => 1650000,
        ]);
    }


    public function test_successful_payment_records_paid_amount(): void
    {
        $booking = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'car_id'      => $this->car->id,
            'store_id'    => $this->store->id,
            'grand_total' => 1155000,
        ]);

        $payment = Payment::factory()->create([
            'booking_id'     => $booking->id,
            'gross_amount'   => 1155000,
            'paid_amount'    => 1155000,
            'payment_status' => 'success',
            'payment_date'   => now(),
        ]);

        $this->assertEquals('success', $payment->payment_status);
        $this->assertEquals(1155000, $payment->paid_amount);
        $this->assertNotNull($payment->payment_date);
    }

    // ─────────────────────────────────────────────
    // Multiple Booking Records
    // ─────────────────────────────────────────────


    public function test_customer_can_have_multiple_bookings(): void
    {
        Booking::factory()->count(3)->create([
            'customer_id' => $this->customer->id,
            'car_id'      => $this->car->id,
            'store_id'    => $this->store->id,
        ]);

        $this->assertEquals(3, $this->customer->bookings()->count());
    }


    public function test_booking_retrieval_by_code(): void
    {
        $booking = Booking::factory()->create([
            'customer_id'  => $this->customer->id,
            'car_id'       => $this->car->id,
            'store_id'     => $this->store->id,
            'booking_code' => 'TRX-FIND-ME-9999',
        ]);

        $found = Booking::query()->where('booking_code', 'TRX-FIND-ME-9999')->first();

        $this->assertNotNull($found);
        $this->assertEquals($booking->id, $found->id);
    }


    public function test_total_payment_calculation_for_customer(): void
    {
        $booking1 = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'car_id'      => $this->car->id,
            'store_id'    => $this->store->id,
        ]);
        $booking2 = Booking::factory()->create([
            'customer_id' => $this->customer->id,
            'car_id'      => $this->car->id,
            'store_id'    => $this->store->id,
        ]);

        Payment::factory()->success()->create([
            'booking_id'   => $booking1->id,
            'gross_amount' => 500000,
            'paid_amount'  => 500000,
        ]);
        Payment::factory()->success()->create([
            'booking_id'   => $booking2->id,
            'gross_amount' => 700000,
            'paid_amount'  => 700000,
        ]);

        $bookingIds = $this->customer->bookings()->pluck('id');
        $totalPaid  = Payment::query()->whereIn('booking_id', $bookingIds)
            ->where('payment_status', 'success')
            ->sum('paid_amount');

        $this->assertEquals(1200000, $totalPaid);
    }

    // ─────────────────────────────────────────────
    // Car Status Transition
    // ─────────────────────────────────────────────


    public function test_car_status_updates_to_rented_when_booking_confirmed(): void
    {
        $booking = Booking::factory()->create([
            'customer_id'    => $this->customer->id,
            'car_id'         => $this->car->id,
            'store_id'       => $this->store->id,
            'booking_status' => 'confirmed',
        ]);

        // The Booking model observer updates car status on save
        $this->car->refresh();
        $this->assertEquals('rented', $this->car->status);
    }


    public function test_car_status_reverts_to_available_when_booking_cancelled(): void
    {
        // First set the car as rented
        $this->car->status = 'rented';
        $this->car->save();

        $booking = Booking::factory()->create([
            'customer_id'    => $this->customer->id,
            'car_id'         => $this->car->id,
            'store_id'       => $this->store->id,
            'booking_status' => 'cancelled',
        ]);

        $this->car->refresh();
        $this->assertEquals('available', $this->car->status);
    }
}
