@section('title', 'Rent ' . $car->car_name . ' – Siliwangi Rental')
@section('description', 'Rent ' . $car->car_name . ' (' . $car->transmission . ') at Siliwangi Rental. Prices from Rp ' . number_format($car->daily_price, 0, ',', '.') . '/day. Clean, fragrant, and well-maintained unit.')
@section('og_image', $car->thumbnail ? asset('storage/' . $car->thumbnail) : asset('images/og-image.jpg'))

<div class="pt-24 pb-16 bg-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-6">
        <!-- BREADCRUMB -->
        <nav class="flex items-center gap-2 text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-10">
            <a href="{{ url('/') }}" class="hover:text-gold transition-colors">Home</a>
            <i class="fas fa-chevron-right text-[8px]"></i>
            <a href="{{ url('/cars') }}" class="hover:text-gold transition-colors">Car</a>
            <i class="fas fa-chevron-right text-[8px]"></i>
            <span class="text-gold">{{ $car->car_name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- LEFT: Image & Details -->
            <div class="lg:col-span-8 animate-[fadeInLeft_0.6s_ease_both]">
                <div class="rounded-3xl overflow-hidden bg-slate-800 border border-white/10 aspect-[16/9] relative group shadow-2xl">
                    @if($car->thumbnail)
                        <img src="{{ asset('storage/' . $car->thumbnail) }}" alt="{{ $car->car_name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-white/10 text-9xl">
                            <i class="fas fa-car"></i>
                        </div>
                    @endif
                    
                    <div class="absolute top-6 left-6 flex flex-col gap-3">
                        <span class="px-4 py-1.5 rounded-full bg-slate-900/80 backdrop-blur-md border border-gold/40 text-gold text-xs font-bold uppercase tracking-widest shadow-xl">
                            {{ $car->type->name ?? 'Premium' }}
                        </span>
                        @if($car->status == 'available')
                            <span class="px-4 py-1.5 rounded-full bg-emerald-500/90 backdrop-blur-md border border-emerald-400 text-white text-xs font-bold uppercase tracking-widest shadow-xl">
                                <i class="fas fa-check-circle mr-1"></i> Available
                            </span>
                        @else
                            <span class="px-4 py-1.5 rounded-full bg-red-500/90 backdrop-blur-md border border-red-400 text-white text-xs font-bold uppercase tracking-widest shadow-xl">
                                <i class="fas fa-ban mr-1"></i> Rented
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mt-12 space-y-12">
                    <!-- SPESIFIKASI -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="p-6 rounded-3xl bg-white/5 border border-white/5 flex flex-col items-center gap-3">
                            <i class="fas fa-users text-gold text-xl"></i>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Capacity</span>
                            <span class="text-white font-bold">{{ $car->seat }} Seats</span>
                        </div>
                        <div class="p-6 rounded-3xl bg-white/5 border border-white/5 flex flex-col items-center gap-3">
                            <i class="fas fa-cog text-gold text-xl"></i>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Transmission</span>
                            <span class="text-white font-bold">{{ $car->transmission }}</span>
                        </div>
                        <div class="p-6 rounded-3xl bg-white/5 border border-white/5 flex flex-col items-center gap-3">
                            <i class="fas fa-gas-pump text-gold text-xl"></i>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Fuel Type</span>
                            <span class="text-white font-bold">{{ $car->fuel_type }}</span>
                        </div>
                        <div class="p-6 rounded-3xl bg-white/5 border border-white/5 flex flex-col items-center gap-3">
                            <i class="fas fa-calendar text-gold text-xl"></i>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Year</span>
                            <span class="text-white font-bold">{{ $car->year }}</span>
                        </div>
                    </div>

                    <!-- DESKRIPSI -->
                    <div class="p-10 rounded-[2.5rem] bg-slate-800/50 border border-white/10">
                        <h3 class="text-white font-black text-2xl mb-6">About <span class="text-gold">{{ $car->car_name }}</span></h3>
                        <div class="text-slate-400 leading-relaxed space-y-4 font-medium">
                            @if($car->description)
                                {!! nl2br(e($car->description)) !!}
                            @else
                                <p>A premium vehicle from Siliwangi Rental that guarantees your travel comfort and safety. The unit is always in clean, fragrant, and regularly maintained condition at an authorized workshop.</p>
                                <p>Equipped with modern safety features and a luxurious interior to support productivity or relaxation during your journey.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Booking Card -->
            <div class="lg:col-span-4 animate-[fadeInRight_0.6s_ease_both]">
                <div class="sticky top-24 space-y-8">
                    <div class="p-10 rounded-[2.5rem] bg-white/5 backdrop-blur-3xl border border-white/10 shadow-2xl overflow-hidden relative">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gold/5 rounded-full blur-3xl"></div>
                        
                        <div class="mb-8">
                            <p class="text-gold text-[10px] font-black uppercase tracking-[0.3em] mb-2">{{ $car->brand->name ?? 'Luxury' }}</p>
                            <h2 class="text-3xl font-black text-white tracking-tight">{{ $car->car_name }}</h2>
                        </div>

                        <div class="py-8 border-y border-white/5 mb-8">
                            <div class="flex items-baseline gap-2">
                                @if($car->is_call_for_price)
                                    <span class="font-poppins font-black text-3xl text-gold uppercase tracking-tighter">Call for Price</span>
                                @else
                                    <span class="font-poppins font-black text-4xl text-gold">Rp {{ number_format($car->daily_price, 0, ',', '.') }}</span>
                                    <span class="text-slate-500 font-medium text-sm">/ Day</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col gap-4">
                            @if($car->status == 'available')
                                @if($car->is_call_for_price)
                                    <a href="https://wa.me/6281234567890?text=Halo%20Admin%2C%20saya%20ingin%20tanya%20harga%20sewa%20{{ urlencode($car->car_name) }}" target="_blank" class="flex-1 px-8 py-5 rounded-2xl bg-emerald-500 text-white font-black text-center hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-emerald-500/20 uppercase tracking-widest flex items-center justify-center gap-3">
                                        <i class="fab fa-whatsapp text-lg"></i>
                                        Inquire Price from Admin
                                    </a>
                                @else
                                    <a href="{{ route('checkout', $car->slug) }}" class="flex-1 px-8 py-5 rounded-2xl bg-gold text-slate-900 font-black text-center hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-gold/20 uppercase tracking-widest flex items-center justify-center gap-3">
                                        <i class="fas fa-key"></i>
                                        Rent Now
                                    </a>
                                @endif
                            @else
                                <button disabled class="flex-1 px-8 py-5 rounded-2xl bg-slate-800 text-slate-500 font-black cursor-not-allowed uppercase tracking-widest flex items-center justify-center gap-3">
                                    <i class="fas fa-ban"></i>
                                    Currently Rented
                                </button>
                            @endif
                        </div>

                        <div class="mt-8 pt-8 border-t border-white/5 flex items-center gap-4 text-slate-500">
                            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center">
                                <i class="fas fa-shield-check text-emerald-500"></i>
                            </div>
                            <p class="text-[10px] font-bold uppercase tracking-widest">All-Risk Insurance & GPS Tracking</p>
                        </div>
                    </div>

                    <div class="bg-white/5 backdrop-blur-3xl rounded-3xl border border-white/10 p-8">
                        <h3 class="text-white font-black text-lg uppercase tracking-widest mb-6">Terms & Conditions</h3>
                        <ul class="space-y-4">
                            @foreach([
                                'Original ID & Driving License (for verification)',
                                'Security Deposit (100% Refundable)',
                                'Fuel returned as received',
                                'No smoking inside the unit',
                                'Usage limited to Java-Bali region'
                            ] as $term)
                                <li class="flex items-start gap-3 text-slate-400 text-xs font-medium">
                                    <i class="fas fa-circle-check text-gold mt-0.5"></i>
                                    {{ $term }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Animate detail page elements
        if (typeof anime !== 'undefined') {
            anime({
                targets: '.animate-\\[fadeInLeft_0\\.6s_ease_both\\]',
                translateX: [-50, 0],
                opacity: [0, 1],
                duration: 800,
                easing: 'easeOutQuad'
            });

            anime({
                targets: '.animate-\\[fadeInRight_0\\.6s_ease_both\\]',
                translateX: [50, 0],
                opacity: [0, 1],
                duration: 800,
                easing: 'easeOutQuad',
                delay: 200
            });
        }
    });
</script>
@endsection
