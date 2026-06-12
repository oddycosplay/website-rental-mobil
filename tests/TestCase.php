<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    /**
     * Seed the Spatie roles required by the application.
     * Called in setUp of tests that need role-based functionality.
     */
    protected function seedRoles(): void
    {
        $roles = ['super-admin', 'admin', 'owner', 'customer', 'finance', 'driver', 'operasional'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }
}
