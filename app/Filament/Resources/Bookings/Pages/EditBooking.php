<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

use App\Models\Customer;

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
            $customer = Customer::create([
                'name' => $data['new_customer_name'] ?? '',
                'email' => $data['new_customer_email'] ?? '',
                'phone' => $data['new_customer_phone'] ?? '',
                'nik' => $data['new_customer_nik'] ?? '',
                'address' => $data['new_customer_address'] ?? '',
                'is_active' => true,
            ]);

            $data['customer_id'] = $customer->id;
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
