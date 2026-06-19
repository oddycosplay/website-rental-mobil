<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Cart extends Component
{
    public array $items = [];

    public function mount(CartService $cartService)
    {
        $this->items = $cartService->get();
    }

    public function removeItem(int $id, CartService $cartService)
    {
        $cartService->remove($id);
        $this->items = $cartService->get();
        
        $this->dispatch('cart-updated');
        session()->flash('success', 'Mobil berhasil dihapus dari keranjang.');
    }

    public function clearCart(CartService $cartService)
    {
        $cartService->clear();
        $this->items = [];
        
        $this->dispatch('cart-updated');
        session()->flash('success', 'Keranjang berhasil dikosongkan.');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.cart');
    }
}
