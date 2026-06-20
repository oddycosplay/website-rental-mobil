<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Store;
use App\Models\Driver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CheckoutComponentTest extends TestCase
{
    use RefreshDatabase;



    public function test_checkout_wizard_can_be_completed_successfully()
    {
        Storage::fake('public');

        $this->mock(\App\Services\MidtransService::class, function ($mock) {
            $mock->shouldReceive('getSnapToken')->andReturn('fake-snap-token-123');
        });

        // 1. Create a Store/Branch
        $store = Store::create([
            'name' => 'Siliwangi Bandung',
            'slug' => 'siliwangi-bandung',
            'code' => 'SLW-BDG',
            'address' => 'Bandung City',
            'phone' => '6281234567890',
            'email' => 'bandung@siliwangi.com',
            'status' => 'active',
        ]);

        // 2. Create a Car
        $car = Car::create([
            'store_id' => $store->id,
            'category' => 'both',
            'car_name' => 'Toyota Avanza Veloz',
            'slug' => 'toyota-avanza-veloz',
            'plate_number' => 'D 1234 ABC',
            'year' => 2023,
            'color' => 'Black',
            'transmission' => 'Automatic',
            'fuel_type' => 'Bensin',
            'daily_price' => 350000,
            'driver_daily_price' => 150000,
            'monthly_price' => 7000000,
            'late_fee' => 50000,
            'is_available' => true,
            'featured' => true,
            'brand_name' => 'Toyota',
            'brand_slug' => 'toyota',
            'type_name' => 'Avanza',
        ]);

        // 3. Mount Livewire Component
        $component = Livewire::test(\App\Livewire\Checkout::class, [
            'car' => $car->slug,
        ]);

        // 4. Fill and submit Step 1
        $component->set('pickup_date', now()->addDay()->format('Y-m-d'))
            ->set('return_date', now()->addDays(3)->format('Y-m-d'))
            ->set('need_type', 'antar')
            ->set('delivery_type', 'standard')
            ->set('pickup_type', 'airport')
            ->set('rental_type', 'daily')
            ->set('branch_id', $store->id)
            ->call('nextStep');

        $component->assertHasNoErrors();
        $this->assertEquals(2, $component->get('step'));

        // 5. Fill Step 2 (Identity)
        $component->set('name', 'John Doe')
            ->set('email', 'john.doe@example.com')
            ->set('phone', '6281234567899')
            ->set('nik', '3273123456789001')
            ->set('sim_number', '9876543210')
            ->set('no_kk', '3273000000000000')
            ->set('nip_nim', '12345678')
            ->set('pekerjaan', 'Karyawan Swasta')
            ->set('address', 'Jalan Merdeka No. 10, Bandung')
            ->call('nextStep');

        $component->assertHasNoErrors();
        $this->assertEquals(3, $component->get('step'));

        // 6. Fill Step 3 (Documents)
        $ktp = UploadedFile::fake()->create('ktp.jpg', 100);
        $sim = UploadedFile::fake()->create('sim.jpg', 100);
        $kk = UploadedFile::fake()->create('kk.jpg', 100);
        $idCard = UploadedFile::fake()->create('id_card.jpg', 100);
        $selfie = 'data:image/jpeg;base64,' . base64_encode('fake-selfie');

        $component->set('ktp_image', $ktp)
            ->set('sim_image', $sim)
            ->set('kk_image', $kk)
            ->set('id_card_image', $idCard)
            ->set('selfie_image', $selfie)
            ->call('nextStep');

        $component->assertHasNoErrors();
        $this->assertEquals(4, $component->get('step'));

        // 7. Fill Step 4 (Options)
        $component->set('with_driver', false)
            ->set('ojol_fee', 25000)
            ->call('nextStep');

        $component->assertHasNoErrors();
        $this->assertEquals(5, $component->get('step'));

        // 8. Submit Step 5 (Finalize)
        $component->call('submit');
        $component->assertHasNoErrors();

        // 9. Assert post-booking state
        $this->assertTrue($component->get('is_finished'));
        $this->assertNotNull($component->get('final_booking_code'));

        // 10. Verify Booking and Payment records were created in DB
        $this->assertDatabaseHas('bookings', [
            'booking_code' => $component->get('final_booking_code'),
            'guest_name' => 'John Doe',
            'grand_total' => $component->get('grand_total'),
        ]);

        $this->assertDatabaseHas('payments', [
            'booking_id' => \App\Models\Booking::first(['*'])->id,
            'payment_status' => 'pending',
        ]);
    }

    public function test_checkout_validation_fails_when_file_is_larger_than_2mb()
    {
        Storage::fake('public');

        // 1. Create a Store/Branch
        $store = Store::create([
            'name' => 'Siliwangi Bandung',
            'slug' => 'siliwangi-bandung',
            'code' => 'SLW-BDG',
            'address' => 'Bandung City',
            'phone' => '6281234567890',
            'email' => 'bandung@siliwangi.com',
            'status' => 'active',
        ]);

        // 2. Create a Car
        $car = Car::create([
            'store_id' => $store->id,
            'category' => 'both',
            'car_name' => 'Toyota Avanza Veloz',
            'slug' => 'toyota-avanza-veloz',
            'plate_number' => 'D 1234 ABC',
            'year' => 2023,
            'color' => 'Black',
            'transmission' => 'Automatic',
            'fuel_type' => 'Bensin',
            'daily_price' => 350000,
            'driver_daily_price' => 150000,
            'monthly_price' => 7000000,
            'late_fee' => 50000,
            'is_available' => true,
            'featured' => true,
            'brand_name' => 'Toyota',
            'brand_slug' => 'toyota',
            'type_name' => 'Avanza',
        ]);

        // 3. Mount Livewire Component
        $component = Livewire::test(\App\Livewire\Checkout::class, [
            'car' => $car->slug,
        ]);

        // 4. Fill and submit Step 1
        $component->set('pickup_date', now()->addDay()->format('Y-m-d'))
            ->set('return_date', now()->addDays(3)->format('Y-m-d'))
            ->set('need_type', 'antar')
            ->set('delivery_type', 'standard')
            ->set('pickup_type', 'airport')
            ->set('rental_type', 'daily')
            ->set('branch_id', $store->id)
            ->call('nextStep');

        // 5. Fill Step 2 (Identity)
        $component->set('name', 'John Doe')
            ->set('email', 'john.doe@example.com')
            ->set('phone', '6281234567899')
            ->set('nik', '3273123456789001')
            ->set('sim_number', '9876543210')
            ->set('no_kk', '3273000000000000')
            ->set('nip_nim', '12345678')
            ->set('pekerjaan', 'Karyawan Swasta')
            ->set('address', 'Jalan Merdeka No. 10, Bandung')
            ->call('nextStep');

        // 6. Fill Step 3 (Documents) with a file larger than 2MB (3000KB)
        $ktpLarge = UploadedFile::fake()->create('ktp_large.jpg', 3000);
        $sim = UploadedFile::fake()->create('sim.jpg', 100);
        $kk = UploadedFile::fake()->create('kk.jpg', 100);
        $idCard = UploadedFile::fake()->create('id_card.jpg', 100);
        $selfie = 'data:image/jpeg;base64,' . base64_encode('fake-selfie');

        $component->set('ktp_image', $ktpLarge)
            ->set('sim_image', $sim)
            ->set('kk_image', $kk)
            ->set('id_card_image', $idCard)
            ->set('selfie_image', $selfie)
            ->call('nextStep');

        $component->assertHasErrors(['ktp_image' => 'max']);
        $this->assertEquals(3, $component->get('step'));
    }
}
