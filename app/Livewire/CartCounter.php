<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;
use Livewire\Attributes\On;

class CartCounter extends Component
{
    public int $count = 0;

    public function mount(CartService $cartService)
    {
        $this->count = $cartService->count();
    }

    #[On('cart-updated')]
    public function updateCount(CartService $cartService)
    {
        $this->count = $cartService->count();
    }

    public function render()
    {
        return <<<'HTML'
        <a href="{{ route('cart') }}" class="relative p-2.5 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 hover:border-gold/30 transition-all duration-300 text-slate-300 hover:text-white">
            <i class="fas fa-shopping-basket"></i>
            @if($count > 0)
                <span class="absolute -top-1 -right-1 w-5 h-5 flex items-center justify-center rounded-full bg-gold text-slate-900 text-[10px] font-black border-2 border-slate-900">
                    {{ $count }}
                </span>
            @endif
        </a>
        HTML;
    }
}
