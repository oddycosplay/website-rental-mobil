<?php

namespace App\Services;

use App\Models\Car;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected $sessionKey = 'rental_cart';

    public function add(Car $car)
    {
        $cart = $this->get();

        if (!isset($cart[$car->id])) {
            $cart[$car->id] = [
                'id' => $car->id,
                'name' => $car->car_name,
                'price' => $car->daily_price,
                'image' => $car->thumbnail,
                'brand' => $car->brand?->name,
                'type' => $car->type?->name,
            ];
        }

        $this->save($cart);
    }

    public function remove(int $carId)
    {
        $cart = $this->get();

        if (isset($cart[$carId])) {
            unset($cart[$carId]);
        }

        $this->save($cart);
    }

    public function get()
    {
        return Session::get($this->sessionKey, []);
    }

    public function clear()
    {
        Session::forget($this->sessionKey);
    }

    public function count()
    {
        return count($this->get());
    }

    public function isEmpty()
    {
        return $this->count() === 0;
    }

    public function getTotalPricePerDay()
    {
        $total = 0;
        foreach ($this->get() as $item) {
            $total += $item['price'];
        }
        return $total;
    }

    protected function save(array $cart)
    {
        Session::put($this->sessionKey, $cart);
    }
}
