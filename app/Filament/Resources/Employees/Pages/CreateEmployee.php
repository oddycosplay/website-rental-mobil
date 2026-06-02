<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function afterCreate(): void
    {
        $data = $this->form->getRawState();
        $employee = $this->record;

        if (!empty($data['has_account'])) {
            // Buat user login baru
            $user = User::create([
                'name' => $employee->name,
                'email' => $employee->email,
                'phone' => $employee->phone,
                'password' => Hash::make($data['password']),
                'store_id' => $employee->store_id,
                'status' => 'active',
            ]);

            // Sinkronisasi Spatie Roles
            if (!empty($data['user_roles'])) {
                $user->roles()->sync($data['user_roles']);
            }

            // Hubungkan user ke record employee
            $employee->update(['user_id' => $user->id]);
        }
    }
}
