<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;

    protected function afterCreate(): void
    {
        $data = $this->form->getRawState();
        $customer = $this->record;

        if (!empty($data['has_account'])) {
            // Buat user login baru
            $user = User::create([
                'name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'password' => Hash::make($data['password']),
                'status' => 'active',
            ]);

            // Assign role customer ke user
            $user->assignRole('customer');

            // Hubungkan user ke record customer
            $customer->update(['user_id' => $user->id]);
        }
    }
}
