@section('title', 'Keranjang Sewa – Siliwangi Rental')

<div class="pt-28 pb-20 bg-[#0A0F1C] min-h-screen text-slate-200 font-poppins selection:bg-gold/30">
    {{-- Background Accents --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-gold/5 rounded-full blur-[120px]"></div>
        <div class="absolute top-[20%] -right-[10%] w-[30%] h-[30%] bg-blue-500/5 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[40%] h-[40%] bg-gold/5 rounded-full blur-[150px]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Header --}}
        <div class="mb-12" data-aos="fade-down">
            <nav class="flex items-center gap-2 text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-4">
                <a href="{{ url('/') }}" class="hover:text-gold transition-colors">Home</a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <a href="{{ url('/cars') }}" class="hover:text-gold transition-colors">Armada</a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <span class="text-gold">Keranjang</span>
            </nav>
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight leading-none mb-4">
                Keranjang <span class="text-gold">Sewa</span>
            </h1>
            <p class="text-slate-500 text-sm font-medium max-w-2xl">Tinjau pilihan armada terbaik Anda sebelum melanjutkan ke proses booking dan pembayaran.</p>
        </div>

        @if(empty($items))
            <div class="bg-white/5 backdrop-blur-3xl rounded-[2.5rem] border border-white/10 p-20 text-center shadow-2xl" data-aos="zoom-in">
                <div class="w-24 h-24 rounded-full bg-gold/10 text-gold flex items-center justify-center text-4xl mx-auto mb-8 shadow-inner">
                    <i class="fas fa-shopping-basket"></i>
                </div>
                <h2 class="text-2xl font-black text-white mb-4">Keranjang Anda Masih Kosong</h2>
                <p class="text-slate-500 text-sm font-medium mb-10 max-w-md mx-auto leading-relaxed">Jelajahi koleksi armada premium kami dan pilih kendaraan yang sesuai dengan gaya serta kebutuhan perjalanan Anda.</p>
                <a href="{{ route('cars.index') }}" class="inline-flex items-center px-10 py-5 bg-gold text-slate-950 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:scale-105 active:scale-95 transition-all shadow-xl shadow-gold/20 gap-3">
                    Jelajahi Armada <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-10">
                <!-- List Items -->
                <div class="w-full lg:w-[65%] space-y-6" data-aos="fade-right">
                    @foreach($items as $id => $item)
                        <div class="group bg-white/5 backdrop-blur-2xl rounded-[2rem] border border-white/10 p-6 flex flex-col md:flex-row items-center gap-8 hover:bg-white/10 transition-all duration-500 border-l-4 border-l-transparent hover:border-l-gold shadow-xl">
                            <div class="w-full md:w-56 aspect-[16/10] rounded-2xl overflow-hidden bg-slate-950 ring-1 ring-white/10 shadow-2xl">
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-800">
                                        <i class="fas fa-car-side text-5xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-grow text-center md:text-left space-y-2">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest bg-gold/10 text-gold mb-2 border border-gold/10">
                                            {{ $item['type'] }}
                                        </span>
                                        <h3 class="text-xl font-black text-white group-hover:text-gold transition-colors">{{ $item['name'] }}</h3>
                                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $item['brand'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Harga Lepas Kunci</p>
                                        <p class="text-xl font-black text-white tracking-tighter">
                                            Rp {{ number_format($item['price'], 0, ',', '.') }}<span class="text-[10px] text-slate-500 font-bold ml-1 uppercase">/Hari</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-shrink-0">
                                <button wire:click="removeItem({{ $id }})" class="w-12 h-12 rounded-xl bg-red-500/5 border border-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center shadow-lg" title="Hapus Item">
                                    <i class="fas fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach

                    <div class="flex flex-col sm:flex-row justify-between items-center gap-6 pt-4">
                        <a href="{{ route('cars.index') }}" class="flex items-center gap-3 px-8 py-4 rounded-xl bg-white/5 border border-white/10 text-slate-400 font-bold text-[10px] uppercase tracking-widest hover:bg-white/10 hover:text-white transition-all">
                            <i class="fas fa-plus"></i> Tambah Unit Lain
                        </a>
                        <button wire:click="clearCart" class="text-slate-600 hover:text-red-400 text-[10px] font-black uppercase tracking-widest transition-colors flex items-center gap-2">
                            <i class="fas fa-circle-xmark"></i> Kosongkan Keranjang
                        </button>
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="w-full lg:w-[35%]" data-aos="fade-left">
                    <div class="bg-white/5 backdrop-blur-3xl rounded-[2.5rem] border border-white/10 p-8 md:p-10 sticky top-28 shadow-2xl border-b-4 border-b-gold">
                        <h2 class="text-xl font-black text-white mb-8 border-b border-white/5 pb-6">Ringkasan <span class="text-gold">Sewa</span></h2>
                        
                        <div class="space-y-6 mb-10">
                            <div class="flex justify-between items-center">
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Jumlah Unit</span>
                                <span class="text-sm font-bold text-white bg-white/5 px-3 py-1 rounded-lg border border-white/10">{{ count($items) }} Mobil</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <div class="text-left">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Sewa</p>
                                    <p class="text-2xl font-black text-white tracking-tighter">Rp {{ number_format(collect($items)->sum('price'), 0, ',', '.') }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Estimasi Lepas Kunci Per Hari</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gold/5 rounded-2xl p-6 mb-10 border border-gold/10">
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded-xl bg-gold/10 text-gold flex items-center justify-center flex-shrink-0 text-xs">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <p class="text-[11px] text-slate-400 leading-relaxed font-medium">
                                    <strong class="text-white uppercase block mb-1">Pemberitahuan:</strong>
                                    Biaya final termasuk pajak, biaya operasional, dan durasi sewa akan dihitung secara otomatis pada langkah checkout.
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('checkout', ['from_cart' => true]) }}" 
                           class="w-full bg-gradient-to-br from-gold via-gold to-gold-dark text-slate-950 text-center py-6 rounded-[1.5rem] font-black text-sm uppercase tracking-[0.2em] shadow-2xl shadow-gold/20 hover:scale-[1.02] active:scale-95 transition-all duration-300 flex items-center justify-center gap-4 group">
                            Proses Booking <i class="fas fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        
                        <div class="mt-8 flex items-center justify-center gap-6 opacity-30 grayscale group-hover:grayscale-0 transition-all duration-500">
                            <i class="fab fa-cc-visa text-2xl text-white"></i>
                            <i class="fab fa-cc-mastercard text-2xl text-white"></i>
                            <i class="fas fa-shield-halved text-xl text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
