<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            'booking', 'car', 'customer', 'driver', 'employee',
            'expense', 'expense_category', 'operational', 'payment', 'store', 'role'
        ];

        $actions = ['view_any', 'view', 'create', 'update', 'delete'];

        foreach ($models as $model) {
            foreach ($actions as $action) {
                $permissionName = "{$action}_{$model}";
                \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permissionName]);
            }
        }
    }
}
