<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Store $store;
    protected User $superAdmin;
    protected User $ownerUser;
    protected User $customerUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRoles();

        $this->store = Store::factory()->create();

        $this->superAdmin = User::factory()->create(['store_id' => $this->store->id]);
        $this->superAdmin->assignRole('super-admin');

        $this->ownerUser = User::factory()->create(['store_id' => $this->store->id]);
        $this->ownerUser->assignRole('owner');

        $this->customerUser = User::factory()->create(['store_id' => $this->store->id]);
        $this->customerUser->assignRole('customer');
    }

    public function test_customer_role_is_filtered_out_from_user_management_index(): void
    {
        // Add another user with non-customer role (e.g. driver)
        $driverUser = User::factory()->create(['store_id' => $this->store->id]);
        $driverUser->assignRole('driver');

        $response = $this->actingAs($this->superAdmin)
            ->get('/dashboard/users');

        $response->assertStatus(200);

        // Ensure customer user is not in the users collection
        $response->assertViewHas('users', function ($users) {
            return !$users->contains($this->customerUser) && $users->contains($this->superAdmin);
        });

        // Ensure customer is not in roles list
        $response->assertViewHas('roles', function ($roles) {
            return !$roles->contains('name', 'customer');
        });
    }

    public function test_non_super_admin_cannot_access_user_actions(): void
    {
        // Attempt to create a user as owner
        $responseStore = $this->from('/dashboard/users')
            ->actingAs($this->ownerUser)
            ->post('/dashboard/users', [
                'name' => 'New Driver',
                'email' => 'newdriver@mail.com',
                'password' => 'password123',
                'role' => 'driver'
            ]);
        $responseStore->assertRedirect('/dashboard/users');
        $responseStore->assertSessionHas('error', 'Hanya Super Admin yang dapat menambahkan user.');

        // Attempt to edit a user as owner
        $responseUpdate = $this->from('/dashboard/users')
            ->actingAs($this->ownerUser)
            ->put('/dashboard/users/' . $this->ownerUser->id, [
                'name' => 'Updated Name',
                'status' => 'active',
                'role' => 'owner'
            ]);
        $responseUpdate->assertRedirect('/dashboard/users');
        $responseUpdate->assertSessionHas('error', 'Hanya Super Admin yang dapat memperbarui user.');

        // Attempt to delete a user as owner
        $responseDelete = $this->from('/dashboard/users')
            ->actingAs($this->ownerUser)
            ->delete('/dashboard/users/' . $this->superAdmin->id);
        $responseDelete->assertRedirect('/dashboard/users');
        $responseDelete->assertSessionHas('error', 'Hanya Super Admin yang dapat menghapus user.');
    }

    public function test_super_admin_can_create_employee(): void
    {
        $response = $this->from('/dashboard/users')
            ->actingAs($this->superAdmin)
            ->post('/dashboard/users', [
                'name' => 'New Finance',
                'email' => 'finance@mail.com',
                'password' => 'password123',
                'role' => 'finance'
            ]);

        $response->assertRedirect('/dashboard/users');
        $response->assertSessionHas('success', 'User berhasil ditambahkan.');

        $this->assertDatabaseHas('users', [
            'email' => 'finance@mail.com',
            'name' => 'New Finance'
        ]);

        $user = User::where('email', '=', 'finance@mail.com', 'and')->first();
        $this->assertTrue($user->hasRole('finance'));
    }

    public function test_cannot_create_user_with_customer_role(): void
    {
        $response = $this->from('/dashboard/users')
            ->actingAs($this->superAdmin)
            ->post('/dashboard/users', [
                'name' => 'Illegal Customer',
                'email' => 'customer@mail.com',
                'password' => 'password123',
                'role' => 'customer'
            ]);

        $response->assertRedirect('/dashboard/users');
        $response->assertSessionHas('error', 'Tidak dapat menambahkan user dengan role customer di sini.');
        $this->assertDatabaseMissing('users', [
            'email' => 'customer@mail.com'
        ]);
    }

    public function test_super_admin_can_update_employee(): void
    {
        $employee = User::factory()->create(['store_id' => $this->store->id]);
        $employee->assignRole('finance');

        $response = $this->from('/dashboard/users')
            ->actingAs($this->superAdmin)
            ->put('/dashboard/users/' . $employee->id, [
                'name' => 'Updated Name',
                'status' => 'inactive',
                'role' => 'driver'
            ]);

        $response->assertRedirect('/dashboard/users');
        $response->assertSessionHas('success', 'User berhasil diperbarui.');

        $employee->refresh();
        $this->assertEquals('Updated Name', $employee->name);
        $this->assertEquals('inactive', $employee->status);
        $this->assertTrue($employee->hasRole('driver'));
        $this->assertFalse($employee->hasRole('finance'));
    }

    public function test_cannot_update_user_to_customer_role(): void
    {
        $employee = User::factory()->create(['store_id' => $this->store->id]);
        $employee->assignRole('finance');

        $response = $this->from('/dashboard/users')
            ->actingAs($this->superAdmin)
            ->put('/dashboard/users/' . $employee->id, [
                'name' => 'Updated Name',
                'status' => 'active',
                'role' => 'customer'
            ]);

        $response->assertRedirect('/dashboard/users');
        $response->assertSessionHas('error', 'Tidak dapat mengubah role user menjadi customer.');
    }

    public function test_super_admin_can_delete_employee(): void
    {
        $employee = User::factory()->create(['store_id' => $this->store->id]);
        $employee->assignRole('finance');

        $response = $this->from('/dashboard/users')
            ->actingAs($this->superAdmin)
            ->delete('/dashboard/users/' . $employee->id);

        $response->assertRedirect('/dashboard/users');
        $response->assertSessionHas('success', 'User berhasil dihapus.');

        $this->assertDatabaseMissing('users', [
            'id' => $employee->id
        ]);
    }

    public function test_super_admin_cannot_demote_themselves(): void
    {
        $response = $this->from('/dashboard/users')
            ->actingAs($this->superAdmin)
            ->put('/dashboard/users/' . $this->superAdmin->id, [
                'name' => 'Super Admin Updated',
                'status' => 'active',
                'role' => 'driver'
            ]);

        $response->assertRedirect('/dashboard/users');
        $response->assertSessionHas('error', 'Anda tidak dapat mengubah role Anda sendiri.');

        $this->superAdmin->refresh();
        $this->assertTrue($this->superAdmin->hasRole('super-admin'));
    }
}
