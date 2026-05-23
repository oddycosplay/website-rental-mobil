<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Car;

class CarCatalog extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $transmission = '';
    public $capacity = '';
    public $price_range = '';
    public $branch = '';
    public $category = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingType() { $this->resetPage(); }
    public function updatingTransmission() { $this->resetPage(); }
    public function updatingCapacity() { $this->resetPage(); }
    public function updatingPriceRange() { $this->resetPage(); }
    public function updatingBranch() { $this->resetPage(); }
    public function updatingCategory() { $this->resetPage(); }

    public function addToCart(int $carId)
    {
        try {
            $car = Car::query()->find($carId);
            if ($car) {
                $cartService = app(\App\Services\CartService::class);
                $cartService->add($car);
                $this->dispatch('cart-updated');
                $this->dispatch('show-toast', type: 'success', message: $car->car_name . ' berhasil ditambahkan ke keranjang!');
            }
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Gagal menambahkan ke keranjang: ' . $e->getMessage());
        }
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $query = Car::query()
            ->where('is_available', true);

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('car_name', 'like', '%' . $this->search . '%')
                    ->orWhere('brand_name', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->type)) {
            $query->where('type_name', $this->type);
        }

        if (!empty($this->transmission)) {
            $query->where('transmission', $this->transmission);
        }

        if (!empty($this->capacity)) {
            if ($this->capacity == '4-5') {
                $query->where('seat', '>=', 4)->where('seat', '<=', 5);
            } elseif ($this->capacity == '6-7') {
                $query->where('seat', '>=', 6)->where('seat', '<=', 7);
            } elseif ($this->capacity == '8+') {
                $query->where('seat', '>=', 8);
            }
        }

        if (!empty($this->price_range)) {
            if ($this->price_range == 'under_500k') {
                $query->where('daily_price', '<', 500000)
                    ->where('is_call_for_price', false);
            } elseif ($this->price_range == '500k_1m') {
                $query->where('daily_price', '>=', 500000)->where('daily_price', '<=', 1000000)
                    ->where('is_call_for_price', false);
            } elseif ($this->price_range == 'over_1m') {
                $query->where(function($q) {
                    $q->where('daily_price', '>', 1000000)
                        ->orWhere('is_call_for_price', true);
                });
            }
        }
        if (!empty($this->branch)) {
            $query->whereHas('branch', function ($q) {
                $q->where('name', $this->branch);
            });
        }

        if (!empty($this->category)) {
            $query->where(function($q) {
                $q->where('category', $this->category)
                    ->orWhere('category', 'both');
            });
        }

        // Always put available cars first, then rented
        $query->orderByRaw("FIELD(status, 'available', 'rented', 'maintenance')", []);
        $query->orderBy('daily_price', 'asc');

        $cars = $query->paginate(9);
        
        $carTypes = \Illuminate\Support\Facades\Cache::remember('car_types_list', 3600, function() {
            $list = new \Illuminate\Support\Collection(Car::query()->whereNotNull('type_name', 'and')->cursor());
            $list = $list->unique('type_name', false);
            $types = [];
            foreach ($list as $car) {
                $types[] = (object) ['name' => $car->type_name];
            }
            return $types;
        });

        $branches = \Illuminate\Support\Facades\Cache::remember('stores_list', 3600, function() {
            return \App\Models\Store::all();
        });

        return view('livewire.car-catalog', compact('cars', 'carTypes', 'branches'));
    }
}
