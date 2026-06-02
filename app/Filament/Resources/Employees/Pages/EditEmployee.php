<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $data = $this->form->getRawState();
        $employee = $this->record;

        if (!empty($data['has_account'])) {
            if ($employee->user_id) {
                // Update user login yang sudah ada
                $user = User::find($employee->user_id);
                if ($user) {
                    $userData = [
                        'name' => $employee->name,
                        'email' => $employee->email,
                        'phone' => $employee->phone,
                        'store_id' => $employee->store_id,
                    ];

                    if (!empty($data['password'])) {
                        $userData['password'] = Hash::make($data['password']);
                    }

                    $user->update($userData);

                    // Sync Spatie Roles
                    if (isset($data['user_roles'])) {
                        $user->roles()->sync($data['user_roles']);
                    }
                }
            } else {
                // Buat user baru jika sebelumnya belum ada
                $user = User::create([
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'phone' => $employee->phone,
                    'password' => Hash::make($data['password'] ?? 'password'),
                    'store_id' => $employee->store_id,
                    'status' => 'active',
                ]);

                // Sync Spatie Roles
                if (!empty($data['user_roles'])) {
                    $user->roles()->sync($data['user_roles']);
                }

                $employee->update(['user_id' => $user->id]);
            }
        } else {
            // Putus hubungan dan hapus user jika akses dinonaktifkan
            if ($employee->user_id) {
                $user = User::find($employee->user_id);
                $employee->update(['user_id' => null]);
                if ($user) {
                    $user->delete();
                }
            }
        }
    }
}
