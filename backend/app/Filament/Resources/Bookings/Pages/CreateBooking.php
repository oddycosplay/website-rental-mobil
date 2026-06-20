<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use Filament\Resources\Pages\CreateRecord;

use App\Models\User;
use Illuminate\Support\Str;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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

        if (empty($data['booking_code'])) {
            $data['booking_code'] = 'TRX-' . strtoupper(Str::random(8));
        }

        if (isset($data['pickup_date'], $data['return_date'])) {
            $pickup = \Carbon\Carbon::parse($data['pickup_date']);
            $return = \Carbon\Carbon::parse($data['return_date']);
            $data['total_day'] = max(1, $pickup->diffInDays($return));
        } else {
            $data['total_day'] = 1;
        }

        $car = \App\Models\Car::query()->find($data['car_id'] ?? null);
        if ($car) {
            $rentalType = $data['rental_type'] ?? 'daily';
            $data['price'] = $rentalType === 'monthly' ? $car->monthly_price : $car->daily_price;
            
            if (!empty($data['with_driver'])) {
                $data['driver_price'] = $car->driver_daily_price ?? 0;
            } else {
                $data['driver_price'] = 0;
            }
        } else {
            $data['price'] = $data['price'] ?? 0;
            $data['driver_price'] = $data['driver_price'] ?? 0;
        }

        $data['extra_price'] = $data['extra_price'] ?? 0;
        $data['late_fee'] = $data['late_fee'] ?? 0;
        $data['tax'] = $data['tax'] ?? 0;
        $data['dp_amount'] = $data['dp_amount'] ?? 0;
        
        $grandTotal = $data['grand_total'] ?? 0;
        $data['remaining_payment'] = max(0, $grandTotal - $data['dp_amount']);

        return $data;
    }
}
