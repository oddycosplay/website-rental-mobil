<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $data = $this->form->getRawState();
        $customer = $this->record;

        if (!empty($data['has_account'])) {
            if ($customer->user_id) {
                // Update user login yang sudah ada
                $user = User::find($customer->user_id);
                if ($user) {
                    $userData = [
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                    ];

                    if (!empty($data['password'])) {
                        $userData['password'] = Hash::make($data['password']);
                    }

                    $user->update($userData);
                }
            } else {
                // Buat user baru jika sebelumnya belum ada
                $user = User::create([
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'password' => Hash::make($data['password'] ?? 'password'),
                    'status' => 'active',
                ]);

                // Assign role customer
                $user->assignRole('customer');

                $customer->update(['user_id' => $user->id]);
            }
        } else {
            // Putus hubungan dan hapus user jika akses login dinonaktifkan
            if ($customer->user_id) {
                $user = User::find($customer->user_id);
                $customer->update(['user_id' => null]);
                if ($user) {
                    $user->delete();
                }
            }
        }
    }
}
