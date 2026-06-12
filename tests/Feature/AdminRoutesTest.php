<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Car;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for admin-panel routes (role-protected).
 * Covers: super-admin access, owner access, role authorization.
 */
class AdminRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected Store $store;
    protected User $adminUser;
    protected User $ownerUser;
    protected User $customerUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();

        $this->store = Store::factory()->create();

        $this->adminUser = User::factory()->create(['store_id' => $this->store->id]);
        $this->adminUser->assignRole('super-admin');

        $this->ownerUser = User::factory()->create(['store_id' => $this->store->id]);
        $this->ownerUser->assignRole('owner');

        $this->customerUser = User::factory()->create();
        $this->customerUser->assignRole('customer');
    }

    // ─────────────────────────────────────────────
    // Role-Based Access Control
    // ─────────────────────────────────────────────


    public function test_customer_cannot_access_admin_bookings(): void
    {
        $response = $this->actingAs($this->customerUser)
            ->get('/dashboard/bookings');

        // Customer should be redirected or get 403 - NOT 200
        $this->assertNotEquals(200, $response->getStatusCode());
    }


    public function test_admin_can_access_customer_list(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/dashboard/customers');

        $response->assertStatus(200);
    }


    public function test_unauthenticated_user_cannot_access_admin_customers(): void
    {
        $response = $this->get('/dashboard/customers');

        $response->assertRedirect('/login');
    }


    public function test_admin_can_access_bookings_list(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/dashboard/bookings');

        $response->assertStatus(200);
    }


    public function test_admin_can_access_car_schedules(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/dashboard/car-schedules');

        $response->assertStatus(200);
    }

    // ─────────────────────────────────────────────
    // Customer Detail API
    // ─────────────────────────────────────────────


    public function test_admin_can_view_customer_detail(): void
    {
        $targetUser = User::factory()->create();
        $targetUser->assignRole('customer');
        Customer::factory()->create(['user_id' => $targetUser->id]);

        $response = $this->actingAs($this->adminUser)
            ->get('/dashboard/customers/' . $targetUser->id);

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $targetUser->id]);
    }

    // ─────────────────────────────────────────────
    // Reports Access (Owner/Finance Only)
    // ─────────────────────────────────────────────


    public function test_owner_can_access_reports(): void
    {
        $response = $this->actingAs($this->ownerUser)
            ->get('/dashboard/reports');

        $response->assertStatus(200);
    }


    public function test_finance_user_can_access_finance_page(): void
    {
        $financeUser = User::factory()->create(['store_id' => $this->store->id]);
        $financeUser->assignRole('finance');

        $response = $this->actingAs($financeUser)
            ->get('/dashboard/finance');

        $response->assertStatus(200);
    }


    public function test_admin_can_access_drivers_page(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get('/dashboard/drivers');

        $response->assertStatus(200);
    }
}
