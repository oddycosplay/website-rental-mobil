<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for the Authentication flow.
 * Covers: login, logout, registration, guest redirect.
 */
class CustomerAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();
    }

    // ─────────────────────────────────────────────
    // Login
    // ─────────────────────────────────────────────


    public function test_customer_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
            'status'   => 'active',
        ]);
        $user->assignRole('customer');

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }


    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correct-password')]);

        $this->post('/login', [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }


    public function test_login_fails_with_non_existent_email(): void
    {
        $this->post('/login', [
            'email'    => 'tidak.terdaftar@example.com',
            'password' => 'password',
        ]);

        $this->assertGuest();
    }


    public function test_login_requires_email_and_password(): void
    {
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    // ─────────────────────────────────────────────
    // Logout
    // ─────────────────────────────────────────────


    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    // ─────────────────────────────────────────────
    // Registration
    // ─────────────────────────────────────────────


    public function test_new_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Budi Santoso Baru',
            'email'                 => 'budi.baru@gmail.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'budi.baru@gmail.com']);
    }


    public function test_registration_fails_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'already@taken.com']);

        $response = $this->post('/register', [
            'name'                  => 'Another User',
            'email'                 => 'already@taken.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }


    public function test_registration_fails_with_password_mismatch(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'different-password',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }

    // ─────────────────────────────────────────────
    // Auth Guard - Protected Routes
    // ─────────────────────────────────────────────


    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }


    public function test_unauthenticated_user_cannot_access_profile(): void
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }


    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }
}
