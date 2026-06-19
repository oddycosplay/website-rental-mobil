<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Customer;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for public-facing pages of Siliwangi Rental.
 * All routes that do NOT require authentication.
 */
class PublicRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    // ─────────────────────────────────────────────
    // Static & Informational Pages
    // ─────────────────────────────────────────────


    public function test_home_page_loads_successfully(): void
    {
        $store = Store::factory()->create();
        Car::factory()->count(3)->create(['store_id' => $store->id, 'is_available' => true]);

        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function test_about_page_loads_successfully(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
    }


    public function test_faq_page_loads_successfully(): void
    {
        $response = $this->get('/faq');

        $response->assertStatus(200);
    }


    public function test_contact_page_loads_successfully(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
    }


    public function test_contact_form_submission_succeeds_with_valid_data(): void
    {
        $response = $this->post('/contact', [
            'name'    => 'Budi Santoso',
            'phone'   => '081234567890',
            'message' => 'Saya ingin tanya soal ketersediaan mobil',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }


    public function test_contact_form_fails_without_required_fields(): void
    {
        $response = $this->post('/contact', []);

        $response->assertSessionHasErrors(['name', 'phone', 'message']);
    }

    // ─────────────────────────────────────────────
    // Car Catalog (Livewire)
    // ─────────────────────────────────────────────


    public function test_car_catalog_page_loads_successfully(): void
    {
        $store = Store::factory()->create();
        Car::factory()->count(3)->create(['store_id' => $store->id, 'is_available' => true]);

        $response = $this->get('/cars');

        $response->assertStatus(200);
    }


    public function test_car_detail_page_loads_with_valid_slug(): void
    {
        $store = Store::factory()->create();
        $car   = Car::factory()->create([
            'store_id' => $store->id,
            'slug'     => 'toyota-avanza-test',
        ]);

        $response = $this->get('/cars/toyota-avanza-test');

        $response->assertStatus(200);
    }


    public function test_car_detail_page_returns_404_for_invalid_slug(): void
    {
        $response = $this->get('/cars/mobil-yang-tidak-ada-sama-sekali');

        $response->assertStatus(404);
    }

    // ─────────────────────────────────────────────
    // Authentication Routes
    // ─────────────────────────────────────────────


    public function test_login_page_loads_successfully(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }


    public function test_register_page_loads_successfully(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }


    public function test_forgot_password_page_loads_successfully(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }
}
