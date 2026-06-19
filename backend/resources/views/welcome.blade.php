@extends('layouts.app')
@section('title','Siliwangi Rental – Premium Car Rental Indonesia')
@section('description','Rent premium, comfortable, safe, and reliable cars in Bandung & West Java.')

@section('content')

<!-- HERO -->
<section class="relative min-h-screen flex items-center pt-20 overflow-hidden bg-slate-900">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=1600&q=80')] bg-cover bg-center opacity-30 transform scale-105 motion-safe:animate-[pulse_10s_infinite]"></div>
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-900/80 to-transparent"></div>
    
    <!-- Floating Orbs -->
    <div class="absolute top-1/4 -left-20 w-96 h-96 bg-gold/10 rounded-full blur-[120px] floating-orb"></div>
    <div class="absolute bottom-1/4 -right-20 w-96 h-96 bg-blue-500/5 rounded-full blur-[120px] floating-orb" style="animation-delay: 2s"></div>

    <div class="container relative z-10 mx-auto px-6 max-w-7xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            
            <!-- Hero Text -->
            <div class="order-2 lg:order-1" data-aos="fade-right">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gold/10 border border-gold/30 text-gold text-xs font-bold tracking-widest uppercase mb-6 backdrop-blur-md">
                    <span class="flex h-2 w-2 rounded-full bg-gold animate-ping"></span>
                    Premium Experience Guaranteed
                </div>
                
                <h1 class="hero-title font-poppins text-5xl md:text-6xl lg:text-7xl font-black leading-[1.1] mb-8 text-white">
                    <span class="hero-text-shadow">Sewa Mobil</span> <span class="bg-clip-text text-transparent bg-gradient-to-r from-gold via-gold-light to-gold-dark">Terpercaya</span><br>
                    <span class="hero-text-shadow">untuk Perjalanan Anda</span>
                </h1>
                
                <div class="hero-content-card bg-white/20 backdrop-blur-md border border-white/10 rounded-3xl p-6 md:p-8 mb-8 max-w-xl">
                    <p class="hero-desc text-slate-300 text-lg lg:text-xl leading-relaxed mb-6 opacity-80">
                        <strong>Siliwangi Rental Trans Nusa</strong> menyediakan layanan sewa mobil lepas kunci maupun dengan driver profesional untuk kebutuhan pribadi, wisata, perjalanan dinas, hingga operasional perusahaan.
                    </p>

                    <div class="grid grid-cols-2 gap-4 mb-8 text-sm font-bold text-white">
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-gold"></i> Armada Terawat</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-gold"></i> Harga Transparan</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-gold"></i> Driver Berpengalaman</div>
                        <div class="flex items-center gap-2"><i class="fas fa-check-circle text-gold"></i> Booking Cepat & Mudah</div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-5 hero-buttons">
                        <a href="{{ url('/cars') }}" class="group relative inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-gold text-slate-900 font-black text-lg overflow-hidden transition-all hover:scale-105 active:scale-95 shadow-2xl shadow-gold/20">
                            <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/30 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                            <i class="fas fa-calendar-alt"></i> Pesan Sekarang
                        </a>
                        <a href="{{ url('/cars') }}" class="btn-lihat-armada inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl border-2 border-white/10 text-white font-bold text-lg backdrop-blur-md hover:bg-white/5 hover:border-gold/50 hover:text-gold transition-all">
                            <i class="fas fa-car-side"></i> Lihat Armada
                        </a>
                    </div>
                </div>
                
                <!-- Trust Stats -->
                <div class="flex flex-wrap gap-8 items-center border-t border-white/5 pt-10">
                    <div class="flex -space-x-3">
                        <img src="https://i.pravatar.cc/100?u=1" class="w-10 h-10 rounded-full border-2 border-slate-900 object-cover">
                        <img src="https://i.pravatar.cc/100?u=2" class="w-10 h-10 rounded-full border-2 border-slate-900 object-cover">
                        <img src="https://i.pravatar.cc/100?u=3" class="w-10 h-10 rounded-full border-2 border-slate-900 object-cover">
                        <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-slate-800 flex items-center justify-center text-[10px] font-bold text-gold stat-number">+12000</div>
                    </div>
                    <div class="text-sm">
                        <div class="flex text-gold text-xs gap-0.5 mb-1">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="text-slate-400 font-medium">Dipercaya oleh ribuan pelanggan</p>
                    </div>
                </div>
            </div>

            <!-- Hero Visual (Interactive Card) -->
            <div class="order-1 lg:order-2" data-aos="zoom-in" data-aos-delay="200">
                <div class="relative">
                    <!-- Decorative shapes -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-gold/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl"></div>
                    
                    <div class="relative p-1 rounded-[2.5rem] bg-gradient-to-br from-gold/50 via-white/5 to-white/5 backdrop-blur-2xl shadow-2xl overflow-hidden group">
                        <div class="bg-slate-900/90 rounded-[2.2rem] p-6 lg:p-8">
                            <div class="relative rounded-3xl overflow-hidden aspect-[4/3] bg-slate-800 group">
                                <img src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800&q=80" alt="Featured Car" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent opacity-60"></div>
                                <div class="absolute bottom-6 left-6 right-6 flex justify-between items-end">
                                    <span class="px-4 py-1.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-bold uppercase tracking-widest">Pilihan Tepat</span>
                                    <div class="bg-gold text-slate-900 px-4 py-1.5 rounded-xl font-black text-sm">Armada Terbaik</div>
                                </div>
                            </div>
                            
                            <div class="mt-8 space-y-6">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h3 class="text-2xl font-black text-white group-hover:text-gold transition-colors">Nyaman & Aman</h3>
                                        <p class="text-slate-400 text-sm mt-1 uppercase tracking-widest font-bold">Layanan Premium</p>
                                    </div>
                                    <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center text-gold text-2xl border border-white/10">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="p-4 rounded-2xl bg-white/5 border border-white/5 text-center">
                                        <i class="fas fa-tachometer-alt text-gold mb-2 block"></i>
                                        <span class="text-xs font-bold text-slate-300">Terawat</span>
                                    </div>
                                    <div class="p-4 rounded-2xl bg-white/5 border border-white/5 text-center">
                                        <i class="fas fa-couch text-gold mb-2 block"></i>
                                        <span class="text-xs font-bold text-slate-300">Premium</span>
                                    </div>
                                    <div class="p-4 rounded-2xl bg-white/5 border border-white/5 text-center">
                                        <i class="fas fa-shield-alt text-gold mb-2 block"></i>
                                        <span class="text-xs font-bold text-slate-300">Aman</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>


<!-- TENTANG KAMI & LAYANAN -->
<section class="py-24 px-4 md:px-8 bg-slate-900">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                <span class="inline-block px-4 py-1.5 rounded-lg bg-gold/10 text-gold text-xs font-black tracking-[0.2em] uppercase mb-6">Tentang Kami</span>
                <h2 class="font-poppins text-4xl font-black text-white mb-6 leading-tight">Selamat Datang di <br><span class="text-gold">Siliwangi Rental Trans Nusa</span></h2>
                <p class="text-slate-400 text-lg leading-relaxed mb-6">
                    Siliwangi Rental Trans Nusa merupakan perusahaan penyedia jasa rental mobil yang berkomitmen memberikan layanan transportasi terbaik dengan armada yang selalu terawat dan siap digunakan.
                </p>
                <p class="text-slate-400 text-lg leading-relaxed">
                    Kami melayani kebutuhan transportasi harian, mingguan, bulanan, perjalanan bisnis, wisata keluarga, hingga kontrak kendaraan perusahaan dengan berbagai pilihan armada sesuai kebutuhan Anda.
                </p>
            </div>
            <div class="space-y-6" data-aos="fade-left">
                <!-- Layanan 1 -->
                <div class="p-8 rounded-3xl bg-slate-800/50 border border-white/5 hover:border-gold/30 transition-colors group relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-gold/5 rounded-full blur-2xl group-hover:bg-gold/10 transition-colors"></div>
                    <div class="flex items-start gap-6">
                        <div class="w-16 h-16 rounded-2xl bg-gold/10 flex items-center justify-center text-gold text-2xl flex-shrink-0">
                            <i class="fas fa-key"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-white mb-2">Sewa Mobil Lepas Kunci</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-4">Nikmati kebebasan berkendara dengan layanan rental mobil tanpa sopir. Cocok untuk kebutuhan pribadi, keluarga, maupun perjalanan bisnis.</p>
                            <ul class="space-y-2 text-sm text-slate-300 font-medium">
                                <li><i class="fas fa-check text-gold mr-2"></i> Proses cepat dan mudah</li>
                                <li><i class="fas fa-check text-gold mr-2"></i> Armada bersih dan terawat</li>
                                <li><i class="fas fa-check text-gold mr-2"></i> Harga terjangkau</li>
                                <li><i class="fas fa-check text-gold mr-2"></i> Pilihan mobil lengkap</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Layanan 2 -->
                <div class="p-8 rounded-3xl bg-slate-800/50 border border-white/5 hover:border-gold/30 transition-colors group relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-500/5 rounded-full blur-2xl group-hover:bg-blue-500/10 transition-colors"></div>
                    <div class="flex items-start gap-6">
                        <div class="w-16 h-16 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-400 text-2xl flex-shrink-0">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-white mb-2">Sewa Mobil Dengan Driver</h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-4">Layanan rental mobil lengkap dengan pengemudi profesional yang siap memberikan kenyamanan dan keamanan selama perjalanan.</p>
                            <ul class="space-y-2 text-sm text-slate-300 font-medium">
                                <li><i class="fas fa-check text-blue-400 mr-2"></i> Driver berpengalaman</li>
                                <li><i class="fas fa-check text-blue-400 mr-2"></i> Perjalanan lebih nyaman</li>
                                <li><i class="fas fa-check text-blue-400 mr-2"></i> Tidak perlu repot mengemudi</li>
                                <li><i class="fas fa-check text-blue-400 mr-2"></i> Cocok untuk tamu perusahaan dan wisata</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="py-24 px-4 md:px-8 overflow-hidden bg-slate-900/50">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-20" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 rounded-lg bg-gold/10 text-gold text-xs font-black tracking-[0.2em] uppercase mb-6">Keunggulan</span>
            <h2 class="font-poppins text-4xl md:text-5xl font-black mb-6 text-white leading-tight">Mengapa Memilih <span class="text-gold">Kami</span>?</h2>
            <p class="text-slate-400 max-w-2xl mx-auto text-lg">Kami berkomitmen memberikan pengalaman sewa mobil terbaik dengan layanan yang transparan dan aman.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 md:gap-10">
            @php $features=[
                ['fas fa-car', 'Armada Lengkap', 'Tersedia berbagai pilihan kendaraan mulai dari city car, MPV, SUV hingga kendaraan premium.'],
                ['fas fa-tags', 'Harga Kompetitif', 'Harga sewa transparan dan sesuai dengan kualitas layanan yang diberikan.'],
                ['fas fa-user-tie', 'Pelayanan Profesional', 'Tim kami siap membantu mulai dari konsultasi armada hingga proses penyewaan.'],
                ['fas fa-shield-alt', 'Aman dan Terpercaya', 'Seluruh armada dirawat secara berkala untuk memastikan kenyamanan dan keselamatan pelanggan.'],
            ]; @endphp

            @foreach($features as $index => $f)
            <div class="group p-8 md:p-10 rounded-3xl bg-slate-900/70 border border-white/10 hover:border-gold/40 hover:bg-slate-900/90 transition-all duration-500 relative overflow-hidden shadow-lg hover:shadow-gold/10 feature-card" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-gold/10 rounded-full blur-3xl group-hover:bg-gold/20 transition-colors"></div>
                <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-slate-800 border border-gold/20 flex items-center justify-center text-gold text-3xl mb-6 md:mb-8 group-hover:scale-110 group-hover:rotate-6 transition-transform duration-500 shadow-xl">
                    <i class="{{ $f[0] }}"></i>
                </div>
                <h3 class="font-poppins font-black text-xl md:text-2xl mb-3 md:mb-4 text-white">{{ $f[1] }}</h3>
                <p class="text-slate-400 leading-relaxed group-hover:text-slate-200 transition-colors text-sm md:text-base">{{ $f[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- RENTAL PERUSAHAAN -->
<section class="py-16 px-4 md:px-8 bg-slate-800/50 border-y border-white/5 relative overflow-hidden">
    <div class="absolute -left-20 top-0 w-64 h-64 bg-blue-500/10 rounded-full blur-[80px]"></div>
    <div class="absolute -right-20 bottom-0 w-64 h-64 bg-gold/10 rounded-full blur-[80px]"></div>
    <div class="max-w-4xl mx-auto text-center relative z-10" data-aos="zoom-in">
        <h2 class="font-poppins text-3xl font-black text-white mb-4">Rental Mobil untuk <span class="text-gold">Perusahaan</span></h2>
        <p class="text-slate-400 text-base md:text-lg mx-auto mb-8 leading-relaxed">
            Kami melayani kebutuhan kendaraan operasional perusahaan, instansi, proyek, maupun kontrak jangka panjang dengan penawaran harga khusus dan dukungan layanan profesional.
        </p>
        <a href="https://wa.me/628973716530" target="_blank" class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-white text-slate-900 font-black text-sm uppercase tracking-widest hover:scale-105 transition-all shadow-xl">
            <i class="fas fa-briefcase"></i> Dapatkan Penawaran
        </a>
    </div>
</section>

<!-- QUICK BOOKING WIDGET -->
<section class="relative z-20 pt-24 px-6 bg-[#0B1120]" data-aos="fade-up" data-aos-offset="0">
    <div class="max-w-6xl mx-auto">
        <div class="bg-slate-900/80 backdrop-blur-3xl border border-white/10 rounded-[2.5rem] p-8 lg:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.5)]">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 pb-8 border-b border-white/5">
                <div>
                    <h2 class="font-poppins font-black text-2xl text-white">Cari <span class="text-gold">Kendaraan</span></h2>
                    <p class="text-slate-400 text-sm mt-1">Temukan armada yang sesuai dengan rencana perjalanan Anda</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Bantuan Hubungi</span>
                    <a href="https://wa.me/628973716530" target="_blank" class="w-12 h-12 rounded-2xl bg-[#25D366]/10 flex items-center justify-center text-[#25D366] border border-[#25D366]/20 hover:bg-[#25D366] hover:text-white transition-all">
                        <i class="fab fa-whatsapp text-lg"></i>
                    </a>
                </div>
            </div>
            
            <form action="{{ url('/cars') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Lokasi Jemput/Ambil</label>
                    <div class="relative group">
                        <i class="fas fa-map-marker-alt absolute left-4 top-1/2 -translate-y-1/2 text-gold opacity-50 group-focus-within:opacity-100 transition-opacity"></i>
                        <select name="branch" class="w-full bg-slate-800/50 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white appearance-none focus:border-gold focus:ring-1 focus:ring-gold/50 transition-all cursor-pointer">
                            <option value="" class="bg-slate-900">Semua Lokasi</option>
                            <option value="Bandung" class="bg-slate-900">Bandung</option>
                            <option value="Jakarta" class="bg-slate-900">Jakarta</option>
                            <option value="Bogor" class="bg-slate-900">Bogor</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs pointer-events-none"></i>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Kategori Mobil</label>
                    <div class="relative group">
                        <i class="fas fa-car absolute left-4 top-1/2 -translate-y-1/2 text-gold opacity-50 group-focus-within:opacity-100 transition-opacity"></i>
                        <select name="type" class="w-full bg-slate-800/50 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white appearance-none focus:border-gold focus:ring-1 focus:ring-gold/50 transition-all cursor-pointer">
                            <option value="" class="bg-slate-900">Semua Tipe</option>
                            <option value="SUV" class="bg-slate-900">Premium SUV</option>
                            <option value="Sedan" class="bg-slate-900">Executive Sedan</option>
                            <option value="MPV" class="bg-slate-900">Family MPV</option>
                            <option value="City Car" class="bg-slate-900">Comfort City Car</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs pointer-events-none"></i>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Estimasi Harga</label>
                    <div class="relative group">
                        <i class="fas fa-tag absolute left-4 top-1/2 -translate-y-1/2 text-gold opacity-50 group-focus-within:opacity-100 transition-opacity"></i>
                        <select name="price_range" class="w-full bg-slate-800/50 border border-white/10 rounded-2xl pl-12 pr-4 py-4 text-white appearance-none focus:border-gold focus:ring-1 focus:ring-gold/50 transition-all cursor-pointer">
                            <option value="" class="bg-slate-900">Semua Harga</option>
                            <option value="under_500k" class="bg-slate-900">Ekonomis (< 500k)</option>
                            <option value="500k_1m" class="bg-slate-900">Reguler (500k - 1M)</option>
                            <option value="over_1m" class="bg-slate-900">Premium (> 1M)</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 text-xs pointer-events-none"></i>
                    </div>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full h-[60px] rounded-2xl bg-gold text-slate-900 font-black text-lg hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-gold/20 flex items-center justify-center gap-3">
                        <i class="fas fa-search"></i> Cari Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- CAR CATALOG PREVIEW -->
<section class="pt-12 pb-24 px-4 md:px-8 bg-[#0B1120]">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-end gap-8 md:gap-10 mb-14 md:mb-20" data-aos="fade-up">
            <div class="max-w-xl">
                <span class="inline-block px-4 py-1.5 rounded-lg bg-gold/10 text-gold text-xs font-black tracking-[0.2em] uppercase mb-6">Katalog</span>
                <h2 class="font-poppins text-4xl md:text-5xl font-black text-white mb-6 leading-tight">Daftar Harga <span class="text-gold">Mulai Dari</span></h2>
                <p class="text-slate-400 text-lg">Pilih dari berbagai kategori mobil unggulan kami untuk melengkapi perjalanan Anda.</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-slate-500 text-sm font-bold">{{ $cars->count() }} <span class="text-gold">Armada</span> Tersedia</span>
                <a href="{{ url('/cars') }}" class="group inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-gold text-slate-900 font-black shadow-lg shadow-gold/20 hover:bg-yellow-400 hover:text-slate-900 border border-gold/40 transition-all text-base md:text-lg">
                    Lihat Semua Harga <i class="fas fa-arrow-right group-hover:translate-x-2 transition-transform"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">
            @forelse($cars as $index => $car)
            <div class="group rounded-[2.5rem] bg-slate-800/40 border border-white/5 overflow-hidden hover:border-gold/30 hover:shadow-2xl hover:shadow-gold/5 transition-all duration-500" data-aos="fade-up" data-aos-delay="{{ min($index * 100, 500) }}">
                <div class="relative aspect-[16/10] overflow-hidden bg-slate-900">
                    @if($car->thumbnail)
                        <img src="{{ Storage::url($car->thumbnail) }}" alt="{{ $car->car_name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-white/5 text-7xl"><i class="fas fa-car-side"></i></div>
                    @endif
                    
                    <div class="absolute top-6 left-6 flex flex-col gap-2">
                        <span class="px-4 py-1.5 rounded-xl bg-slate-900/80 backdrop-blur-md border border-gold/30 text-gold text-[10px] font-black uppercase tracking-[0.1em]">
                            {{ $car->type_name ?? 'Premium' }}
                        </span>
                        @if($car->category && $car->category !== 'both')
                        <span class="px-4 py-1.5 rounded-xl bg-slate-900/80 backdrop-blur-md border border-white/10 text-slate-300 text-[10px] font-bold uppercase tracking-widest">
                            <i class="fas fa-tag text-gold mr-1"></i> {{ ucfirst($car->category) }}
                        </span>
                        @endif
                    </div>

                    @if($car->status == 'rented')
                        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] flex items-center justify-center">
                            <span class="px-6 py-2 rounded-full bg-red-500 text-white text-xs font-black uppercase tracking-widest shadow-xl">Booked Out</span>
                        </div>
                    @elseif($car->status == 'maintenance')
                        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] flex items-center justify-center">
                            <span class="px-6 py-2 rounded-full bg-amber-500 text-white text-xs font-black uppercase tracking-widest shadow-xl">Maintenance</span>
                        </div>
                    @endif
                </div>
                
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="font-poppins font-black text-2xl text-white group-hover:text-gold transition-colors">{{ $car->car_name }}</h3>
                            <p class="text-slate-500 text-xs font-bold mt-1 uppercase tracking-widest">{{ $car->brand_name ?? 'Siliwangi Elite' }}</p>
                        </div>
                        <div class="flex items-center gap-1.5 text-gold bg-gold/10 px-3 py-1 rounded-lg">
                            <i class="fas fa-star text-xs"></i> <span class="font-black text-sm">4.9</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 mb-8">
                        <div class="flex flex-col items-center gap-2 p-3 rounded-2xl bg-white/5 border border-white/5">
                            <i class="fas fa-cog text-gold text-sm"></i>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $car->transmission }}</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 p-3 rounded-2xl bg-white/5 border border-white/5">
                            <i class="fas fa-gas-pump text-gold text-sm"></i>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $car->fuel_type }}</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 p-3 rounded-2xl bg-white/5 border border-white/5">
                            <i class="fas fa-users text-gold text-sm"></i>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $car->seat }} Seats</span>
                        </div>
                    </div>
                    
                    <div class="pt-8 border-t border-white/5 space-y-5">
                        <div class="flex justify-between items-end">
                            <div class="space-y-4">
                                @if($car->is_call_for_price)
                                    <div>
                                        <div class="text-gold font-black text-2xl tracking-tight">Hubungi Kami</div>
                                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">untuk harga terbaik</span>
                                    </div>
                                @else
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <i class="fas fa-key text-slate-500 text-[10px]"></i>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lepas Kunci</span>
                                        </div>
                                        <div class="text-white font-black text-lg tracking-tight">Rp {{ number_format($car->daily_price, 0, ',', '.') }} <span class="text-[10px] font-normal text-slate-500">/hari</span></div>
                                    </div>
                                    @if($car->driver_daily_price)
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <i class="fas fa-user-tie text-slate-500 text-[10px]"></i>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Dengan Supir</span>
                                        </div>
                                        <div class="text-gold font-black text-lg tracking-tight">Rp {{ number_format($car->driver_daily_price, 0, ',', '.') }} <span class="text-[10px] font-normal text-slate-500">/hari</span></div>
                                    </div>
                                    @endif
                                @endif
                            </div>
                            <div class="text-right pb-1">
                                <span class="text-[10px] font-bold {{ $car->status == 'available' ? 'text-emerald-500' : ($car->status == 'rented' ? 'text-red-500' : 'text-amber-500') }} uppercase tracking-widest bg-slate-900 px-3 py-1.5 rounded-full border border-white/5 shadow-inner whitespace-nowrap">
                                    <i class="fas {{ $car->status == 'available' ? 'fa-check-circle' : ($car->status == 'rented' ? 'fa-times-circle' : 'fa-wrench') }} mr-1"></i>
                                    {{ $car->status == 'available' ? 'Tersedia' : ($car->status == 'rented' ? 'Disewa' : 'Perbaikan') }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('cars.show', $car->slug) }}" class="w-full py-3.5 rounded-2xl bg-white/5 border border-white/10 text-white font-bold text-xs flex items-center justify-center gap-2 hover:bg-white/10 transition-all uppercase tracking-widest">
                                <i class="fas fa-info-circle text-gold"></i> Detail
                            </a>
                            
                            @if($car->status == 'rented')
                                <button disabled class="w-full py-3.5 rounded-2xl bg-slate-800 text-slate-600 font-black text-xs cursor-not-allowed uppercase tracking-widest">Disewa</button>
                            @elseif($car->status == 'maintenance')
                                <button disabled class="w-full py-3.5 rounded-2xl bg-slate-800 text-slate-600 font-black text-xs cursor-not-allowed uppercase tracking-widest">Perbaikan</button>
                            @else
                                <a href="{{ route('checkout', $car->slug) }}" class="w-full py-3.5 rounded-2xl bg-gold text-slate-900 font-black text-xs hover:scale-105 active:scale-95 transition-all shadow-lg shadow-gold/20 flex items-center justify-center gap-2 uppercase tracking-widest">
                                    <i class="fas fa-key"></i> Booking
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-24 bg-slate-800/20 rounded-[3rem] border border-dashed border-white/10">
                <i class="fas fa-car-side text-6xl text-slate-700 mb-6"></i>
                <h3 class="text-xl font-bold text-slate-400">Armada Sedang Tidak Tersedia</h3>
                <p class="text-slate-600 mt-2">Silakan hubungi admin via WhatsApp untuk informasi lebih lanjut.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-14 text-center bg-gold/10 border border-gold/20 p-6 rounded-2xl max-w-3xl mx-auto" data-aos="fade-up">
            <p class="text-gold font-bold text-sm md:text-base"><i class="fas fa-info-circle mr-2"></i> Untuk kendaraan premium, harga bulanan, dan kebutuhan perusahaan silakan hubungi Admin.</p>
        </div>
    </div>
</section>

<!-- TESTIMONIALS (Premium Slider feel) -->
<section class="py-24 px-4 md:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col items-center text-center mb-16 md:mb-20" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 rounded-lg bg-gold/10 text-gold text-xs font-black tracking-[0.2em] uppercase mb-6">Ulasan</span>
            <h2 class="font-poppins text-4xl md:text-5xl font-black text-white mb-6">Testimoni <span class="text-gold">Pelanggan</span></h2>
            <div class="flex text-gold gap-1 text-lg mb-8">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">
            @php $testis=[
                ['Pelayanan cepat, armada bersih dan nyaman. Sangat direkomendasikan untuk kebutuhan perjalanan keluarga.', 'Pelanggan Setia', 'Keluarga', 'P'],
                ['Driver ramah dan profesional. Perjalanan bisnis menjadi lebih nyaman.', 'Corporate Client', 'Bisnis', 'C'],
                ['Proses booking mudah dan harga sesuai. Akan menggunakan layanan ini kembali.', 'Wisatawan', 'Personal', 'W'],
            ]; @endphp
            @foreach($testis as $index => $t)
            <div class="p-8 md:p-10 rounded-3xl bg-slate-900/80 border border-white/10 relative overflow-hidden group hover:bg-slate-900/90 transition-all duration-500 shadow-lg hover:shadow-gold/10" data-aos="fade-up" data-aos-delay="{{ $index * 150 }}">
                <i class="fas fa-quote-left absolute -top-4 -left-4 text-gold/20 text-7xl group-hover:text-gold/30 transition-colors"></i>
                <p class="text-slate-200 text-base md:text-lg leading-relaxed mb-10 relative z-10 font-medium">"{{ $t[0] }}"</p>
                <div class="flex items-center gap-5 pt-8 border-t border-white/10">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-gold to-yellow-400 flex items-center justify-center text-slate-900 font-black text-xl shadow-lg">{{ $t[3] }}</div>
                    <div>
                        <div class="font-black text-white text-base md:text-lg tracking-tight">{{ $t[1] }}</div>
                        <div class="text-gold text-xs font-bold uppercase tracking-widest mt-1">{{ $t[2] }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA (High Impact) -->
<section class="py-24 px-4 md:px-8 relative overflow-hidden">
    <div class="absolute inset-0 bg-gold/10 blur-[120px] animate-pulse"></div>
    <div class="max-w-5xl mx-auto relative z-10">
        <div class="p-8 md:p-16 rounded-[2.5rem] bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 border border-white/10 text-center shadow-2xl overflow-hidden relative group">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gold/10 rounded-full blur-[80px] group-hover:bg-gold/20 transition-colors"></div>
            <span class="inline-block px-4 py-1.5 rounded-lg bg-gold/10 text-gold text-xs font-black tracking-[0.2em] uppercase mb-6">Hubungi Kami</span>
            <h2 class="font-poppins text-3xl md:text-5xl lg:text-6xl font-black text-white mb-6 leading-tight" data-aos="fade-up">
                Siap memesan <br><span class="text-gold">Kendaraan?</span>
            </h2>
            <p class="text-slate-400 text-lg md:text-xl mb-10 md:mb-14 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Tim <strong>Siliwangi Rental Trans Nusa</strong> siap membantu Anda menemukan kendaraan yang tepat untuk setiap kebutuhan perjalanan.<br><br>
                <span class="text-white font-bold">Booking Sekarang dan Nikmati Perjalanan yang Nyaman, Aman, dan Terpercaya.</span>
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-5 md:gap-8" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ url('/cars') }}" class="px-10 md:px-12 py-4 md:py-5 rounded-2xl bg-gold text-slate-900 font-black text-lg md:text-xl hover:scale-105 active:scale-95 transition-all shadow-2xl shadow-gold/30 uppercase tracking-widest">
                    Pesan Sekarang
                </a>
                <a href="https://wa.me/628973716530" target="_blank" class="px-10 md:px-12 py-4 md:py-5 rounded-2xl border-2 border-[#25D366] text-[#25D366] font-black text-lg md:text-xl hover:bg-[#25D366]/10 transition-all uppercase tracking-widest flex items-center justify-center gap-3">
                    <i class="fab fa-whatsapp"></i> Chat Admin
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Hero Text Reveal Animation
        anime.timeline({
            easing: 'easeOutExpo',
        })
        .add({
            targets: '.font-poppins h1',
            translateY: [100, 0],
            opacity: [0, 1],
            duration: 1200,
            delay: 300
        })
        .add({
            targets: '.font-poppins p',
            translateY: [50, 0],
            opacity: [0, 1],
            duration: 1000,
            offset: '-=800'
        })
        .add({
            targets: '.hero-buttons a',
            scale: [0.5, 1],
            opacity: [0, 1],
            duration: 800,
            delay: anime.stagger(150),
            offset: '-=600'
        });

        // Floating Orbs Animation
        anime({
            targets: '.floating-orb',
            translateX: () => anime.random(-30, 30),
            translateY: () => anime.random(-30, 30),
            duration: 4000,
            direction: 'alternate',
            loop: true,
            easing: 'easeInOutQuad'
        });

        // Feature Cards Stagger
        anime({
            targets: '.feature-card',
            translateY: [40, 0],
            opacity: [0, 1],
            delay: anime.stagger(100, {start: 500}),
            duration: 1200,
            easing: 'easeOutElastic(1, .8)'
        });

        // Stats Counter Animation
        const stats = document.querySelectorAll('.stat-number');
        stats.forEach(stat => {
            const targetValue = parseInt(stat.innerText.replace(/\D/g, ''));
            anime({
                targets: stat,
                innerText: [0, targetValue],
                round: 1,
                easing: 'easeInOutExpo',
                duration: 2000,
                delay: 1000
            });
        });

        // Magnetic Button Effect
        const magneticButtons = document.querySelectorAll('.hero-buttons a');
        magneticButtons.forEach(btn => {
            btn.addEventListener('mousemove', (e) => {
                const rect = btn.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;

                anime({
                    targets: btn,
                    translateX: x * 0.3,
                    translateY: y * 0.3,
                    scale: 1.05,
                    duration: 600,
                    easing: 'easeOutExpo'
                });
            });

            btn.addEventListener('mouseleave', () => {
                anime({
                    targets: btn,
                    translateX: 0,
                    translateY: 0,
                    scale: 1,
                    duration: 800,
                    easing: 'easeOutElastic(1, .6)'
                });
            });
        });
    });
</script>
@endsection
