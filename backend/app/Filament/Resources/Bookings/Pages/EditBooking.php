<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

use App\Models\User;
use Illuminate\Support\Str;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['is_new_customer'])) {
            $email = !empty($data['new_customer_email']) 
                ? $data['new_customer_email'] 
                : (!empty($data['new_customer_phone']) 
                    ? $data['new_customer_phone'] . '@siliwangirental.com' 
                    : 'cust_' . Str::random(5) . '@siliwangirental.com');

            $customer = User::create([
                'name' => $data['new_customer_name'] ?? '',
                'email' => $email,
                'phone' => $data['new_customer_phone'] ?? '',
                'nik' => $data['new_customer_nik'] ?? '',
                'address' => $data['new_customer_address'] ?? '',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'status' => 'active',
            ]);

            $customer->assignRole('customer');

            $data['user_id'] = $customer->id;
        }

        unset($data['is_new_customer']);
        unset($data['new_customer_name']);
        unset($data['new_customer_email']);
        unset($data['new_customer_phone']);
        unset($data['new_customer_nik']);
        unset($data['new_customer_address']);

        return $data;
    }
}
