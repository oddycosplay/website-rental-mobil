@section('title','Vehicle Car – Siliwangi Rental')
@section('description','Choose a premium vehicle to suit your needs. SUV, Sedan, MPV, Minibus available.')

<div>
    <!-- PAGE HERO -->
    <section class="pt-32 pb-16 bg-gradient-to-br from-slate-900 via-[#162032] to-slate-800 border-b border-gold/10 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-gold/10 border border-gold/30 text-gold text-xs font-bold tracking-widest uppercase mb-4">Premium Car</span>
            <h1 class="font-poppins text-4xl md:text-5xl font-extrabold mb-4">Choose Your <span class="text-gold">Dream</span> Vehicle</h1>
            <p class="text-white text-lg max-w-2xl mx-auto">Hundreds of premium vehicles ready for use — SUV, Sedan, MPV, to Minibus for all your travel needs.</p>
        </div>
    </section>

    <section class="py-16 px-6 min-h-[500px]">
        <div class="max-w-7xl mx-auto">
            <!-- FILTER BAR (Glassmorphism) -->
            <div class="sticky top-20 z-30 mb-12" data-aos="fade-down">
                <div class="bg-slate-900/60 backdrop-blur-2xl border border-white/10 rounded-3xl p-4 lg:p-6 shadow-[0_15px_40px_rgba(0,0,0,0.4)] flex flex-col lg:flex-row gap-5 items-center">
                    <div class="relative w-full lg:flex-1 group">
                        <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gold/50 group-focus-within:text-gold transition-colors"></i>
                        <input type="text" wire:model.live.debounce.300ms="search" class="w-full bg-slate-800/50 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white text-sm focus:border-gold focus:ring-1 focus:ring-gold/50 transition-all placeholder:text-slate-500" placeholder="Search vehicle name or brand...">
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 w-full lg:w-auto">
                        <div class="relative hidden">
                            <select wire:model.live="branch" class="w-full bg-slate-800/50 border border-white/10 rounded-2xl px-5 py-4 text-white text-sm focus:border-gold focus:ring-1 focus:ring-gold/50 transition-all appearance-none cursor-pointer">
                                <option value="" class="bg-slate-900">All Branches</option>
                                @foreach($branches as $br)
                                    <option value="{{ $br->name }}" class="bg-slate-900">{{ $br->name }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 text-[10px] pointer-events-none"></i>
                        </div>

                        <div class="relative">
                            <select wire:model.live="type" class="w-full bg-slate-800/50 border border-white/10 rounded-2xl px-5 py-4 text-white text-sm focus:border-gold focus:ring-1 focus:ring-gold/50 transition-all appearance-none cursor-pointer">
                                <option value="" class="bg-slate-900">All Types</option>
                                @foreach($carTypes as $ct)
                                    <option value="{{ $ct->name }}" class="bg-slate-900">{{ $ct->name }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 text-[10px] pointer-events-none"></i>
                        </div>

                        <div class="relative">
                            <select wire:model.live="category" class="w-full bg-slate-800/50 border border-white/10 rounded-2xl px-5 py-4 text-white text-sm focus:border-gold focus:ring-1 focus:ring-gold/50 transition-all appearance-none cursor-pointer">
                                <option value="" class="bg-slate-900">Semua Kategori</option>
                                <option value="pribadi" class="bg-slate-900">Pribadi</option>
                                <option value="perusahaan" class="bg-slate-900">Perusahaan</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 text-[10px] pointer-events-none"></i>
                        </div>
                        
                        <div class="relative">
                            <select wire:model.live="transmission" class="w-full bg-slate-800/50 border border-white/10 rounded-2xl px-5 py-4 text-white text-sm focus:border-gold focus:ring-1 focus:ring-gold/50 transition-all appearance-none cursor-pointer">
                                <option value="" class="bg-slate-900">Transmission</option>
                                <option value="Automatic" class="bg-slate-900">Automatic</option>
                                <option value="Manual" class="bg-slate-900">Manual</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 text-[10px] pointer-events-none"></i>
                        </div>
                        
                        <div class="relative">
                            <select wire:model.live="capacity" class="w-full bg-slate-800/50 border border-white/10 rounded-2xl px-5 py-4 text-white text-sm focus:border-gold focus:ring-1 focus:ring-gold/50 transition-all appearance-none cursor-pointer">
                                <option value="" class="bg-slate-900">Capacity</option>
                                <option value="4-5" class="bg-slate-900">4-5 Seats</option>
                                <option value="6-7" class="bg-slate-900">6-7 Seats</option>
                                <option value="8+" class="bg-slate-900">8+ Seats</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 text-[10px] pointer-events-none"></i>
                        </div>
                        
                        <div class="relative col-span-2 md:col-span-1">
                            <select wire:model.live="price_range" class="w-full bg-slate-800/50 border border-white/10 rounded-2xl px-5 py-4 text-white text-sm focus:border-gold focus:ring-1 focus:ring-gold/50 transition-all appearance-none cursor-pointer">
                                <option value="" class="bg-slate-900">Price Range</option>
                                <option value="under_500k" class="bg-slate-900">< 500k</option>
                                <option value="500k_1m" class="bg-slate-900">500k - 1M</option>
                                <option value="over_1m" class="bg-slate-900">> 1M</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 text-[10px] pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CAR GRID & SKELETON -->
            <div class="relative">
                <div wire:loading.grid class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @for($i=0; $i<6; $i++)
                    <div class="rounded-[2.5rem] bg-slate-800/40 border border-white/5 overflow-hidden animate-pulse">
                        <div class="aspect-[16/10] bg-slate-800"></div>
                        <div class="p-8 space-y-6">
                            <div class="flex justify-between">
                                <div class="h-6 w-1/2 bg-slate-800 rounded"></div>
                                <div class="h-6 w-12 bg-slate-800 rounded"></div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="h-10 bg-slate-800 rounded-xl"></div>
                                <div class="h-10 bg-slate-800 rounded-xl"></div>
                                <div class="h-10 bg-slate-800 rounded-xl"></div>
                            </div>
                            <div class="pt-8 border-t border-white/5 flex justify-between">
                                <div class="h-10 w-24 bg-slate-800 rounded-xl"></div>
                                <div class="h-12 w-32 bg-slate-800 rounded-xl"></div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>

                <div wire:loading.remove class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @forelse($cars as $car)
                    <div class="group rounded-[2.5rem] bg-slate-800/40 border border-white/5 overflow-hidden hover:border-gold/30 hover:shadow-2xl hover:shadow-gold/5 transition-all duration-500 flex flex-col" data-aos="fade-up">
                        <div class="relative aspect-[16/10] overflow-hidden bg-slate-900 flex-shrink-0">
                            @if($car->thumbnail)
                                <img src="{{ asset('storage/' . $car->thumbnail) }}" alt="{{ $car->car_name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center text-white/5 text-7xl"><i class="fas fa-car-side"></i></div>
                            @endif
                            
                            <div class="absolute top-6 left-6 flex flex-col gap-2">
                                <span class="px-4 py-1.5 rounded-xl bg-slate-900/80 backdrop-blur-md border border-gold/30 text-gold text-[10px] font-black uppercase tracking-[0.1em]">
                                    {{ $car->type->name ?? 'Premium' }}
                                </span>
                                <span class="px-4 py-1.5 rounded-xl bg-slate-900/80 backdrop-blur-md border border-white/10 text-slate-300 text-[10px] font-bold uppercase tracking-widest">
                                    <i class="fas fa-tag text-gold mr-1"></i> {{ $car->is_call_for_price ? 'Perusahaan' : 'Pribadi & Perusahaan' }}
                                </span>
                            </div>

                            @if($car->status == 'rented')
                                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] flex items-center justify-center">
                                    <span class="px-6 py-2 rounded-full bg-red-500 text-white text-xs font-black uppercase tracking-widest shadow-xl">Booked Out</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-8 flex flex-col flex-1">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="font-poppins font-black text-2xl text-white group-hover:text-gold transition-colors">{{ $car->car_name }}</h3>
                                    <p class="text-slate-500 text-xs font-bold mt-1 uppercase tracking-widest">{{ $car->brand->name ?? 'Siliwangi Elite' }}</p>
                                </div>
                                <div class="flex items-center gap-1.5 text-gold bg-gold/10 px-3 py-1 rounded-lg border border-gold/20">
                                    <i class="fas fa-star text-xs"></i> <span class="font-black text-sm">4.9</span>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-3 gap-4 mb-8">
                                <div class="flex flex-col items-center gap-2 p-3 rounded-2xl bg-white/5 border border-white/5 group-hover:bg-gold/5 transition-colors">
                                    <i class="fas fa-cog text-gold text-sm"></i>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $car->transmission }}</span>
                                </div>
                                <div class="flex flex-col items-center gap-2 p-3 rounded-2xl bg-white/5 border border-white/5 group-hover:bg-gold/5 transition-colors">
                                    <i class="fas fa-gas-pump text-gold text-sm"></i>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $car->fuel_type }}</span>
                                </div>
                                <div class="flex flex-col items-center gap-2 p-3 rounded-2xl bg-white/5 border border-white/5 group-hover:bg-gold/5 transition-colors">
                                    <i class="fas fa-users text-gold text-sm"></i>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $car->seat }} Seats</span>
                                </div>
                            </div>
                            
                            <div class="pt-8 border-t border-white/5 mt-auto space-y-6">
                                <div class="flex justify-between items-end">
                                    <div>
                                        @if($car->is_call_for_price)
                                            <div class="text-gold font-black text-xl tracking-tight">Call for Price</div>
                                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">contact admin</span>
                                        @else
                                            <div class="space-y-1">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">🔑 Lepas Kunci:</span>
                                                    <span class="text-gold font-black text-sm">Rp {{ number_format($car->daily_price, 0, ',', '.') }}<span class="text-[9px] text-slate-500 font-bold normal-case">/hari</span></span>
                                                </div>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">👨‍✈️ Sama Driver:</span>
                                                    <span class="text-gold font-black text-sm">Rp {{ number_format($car->daily_price + $car->driver_daily_price, 0, ',', '.') }}<span class="text-[9px] text-slate-500 font-bold normal-case">/hari</span></span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-1">Status</span>
                                        <span class="text-[10px] font-bold {{ $car->status == 'available' ? 'text-emerald-500' : 'text-red-500' }} uppercase tracking-widest">
                                            <i class="fas {{ $car->status == 'available' ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                            {{ $car->status == 'available' ? 'Available' : 'Rented' }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <a href="{{ route('cars.show', $car->slug) }}" class="w-full py-3.5 rounded-2xl bg-white/5 border border-white/10 text-white font-bold text-xs flex items-center justify-center gap-2 hover:bg-white/10 transition-all uppercase tracking-widest">
                                        <i class="fas fa-info-circle text-gold"></i> Detail
                                    </a>
                                    
                                    @if($car->status == 'available' && !$car->is_call_for_price)
                                        <button wire:click="addToCart({{ $car->id }})" class="w-full py-3.5 rounded-2xl bg-white/5 border border-white/10 text-white font-bold text-xs flex items-center justify-center gap-2 hover:bg-white/10 transition-all uppercase tracking-widest group/cart">
                                            <i class="fas fa-shopping-basket text-gold group-hover/cart:scale-110 transition-transform"></i> + Cart
                                        </button>
                                    @else
                                        <div class="w-full py-3.5 rounded-2xl bg-slate-800/50 border border-white/5 text-slate-500 font-bold text-[10px] flex items-center justify-center gap-2 uppercase tracking-widest cursor-not-allowed">
                                            N/A
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="w-full">
                                    @if($car->status == 'rented')
                                        <button disabled class="w-full py-3.5 rounded-2xl bg-slate-800 text-slate-600 font-black text-xs cursor-not-allowed uppercase tracking-widest">Rented</button>
                                    @elseif($car->is_call_for_price)
                                        <a href="https://wa.me/6281234567890?text=Halo%20Admin%2C%20saya%20ingin%20tanya%20harga%20sewa%20{{ urlencode($car->car_name) }}" target="_blank" class="w-full py-3.5 rounded-2xl bg-emerald-500 text-white font-black text-xs hover:scale-105 active:scale-95 transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 uppercase tracking-widest">
                                            <i class="fab fa-whatsapp"></i> Chat Admin
                                        </a>
                                    @else
                                        <a href="{{ route('checkout', $car->slug) }}" class="w-full py-3.5 rounded-2xl bg-gold text-slate-900 font-black text-xs hover:scale-105 active:scale-95 transition-all shadow-lg shadow-gold/20 flex items-center justify-center gap-2 uppercase tracking-widest">
                                            <i class="fas fa-key"></i> Book Now
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-24 text-center bg-slate-800/20 rounded-[3rem] border border-dashed border-white/10" data-aos="fade-up">
                        <i class="fas fa-car-side text-6xl text-slate-700 mb-6"></i>
                        <h3 class="text-xl font-bold text-slate-400">Car Currently Unavailable</h3>
                        <p class="text-slate-600 mt-2 mb-8">We couldn't find any vehicles matching your criteria.</p>
                        <button wire:click="$set('search', ''); $set('type', ''); $set('category', ''); $set('transmission', ''); $set('capacity', ''); $set('price_range', ''); $set('branch', '');" class="px-8 py-3 rounded-xl border border-gold text-gold hover:bg-gold hover:text-slate-900 transition-all font-bold text-sm uppercase tracking-widest">
                            Reset All Filters
                        </button>
                    </div>
                    @endforelse
                </div>
            </div>
            
            <div class="mt-20">
                {{ $cars->links() }}
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Animate car cards on initial load
            if (typeof anime !== 'undefined') {
                anime({
                    targets: '.group[data-aos]',
                    translateY: [30, 0],
                    opacity: [0, 1],
                    delay: anime.stagger(100),
                    duration: 800,
                    easing: 'easeOutQuad'
                });
            }
        });

        // Re-run animations after Livewire updates
        document.addEventListener('livewire:navigated', () => {
            if (typeof anime !== 'undefined') {
                anime({
                    targets: '.group[data-aos]',
                    translateY: [20, 0],
                    opacity: [0, 1],
                    delay: anime.stagger(50),
                    duration: 600,
                    easing: 'easeOutQuad'
                });
            }
        });
    </script>
    @endpush
</div>
