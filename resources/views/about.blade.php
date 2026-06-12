@extends('layouts.app')
@section('title', 'Tentang Kami – Siliwangi Rental')
@section('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.02);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .glass-card-hover:hover {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(212, 175, 55, 0.3);
        transform: translateY(-5px);
    }

    .text-gradient-gold {
        background: linear-gradient(135deg, #FFD700 0%, #FDB931 50%, #D4AF37 100%);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .bg-grid-pattern {
        background-image: radial-gradient(rgba(212, 175, 55, 0.05) 1px, transparent 1px);
        background-size: 30px 30px;
    }

    .shimmer {
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
        background-size: 200% 100%;
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }

        100% {
            background-position: 200% 0;
        }
    }

    .blob-animate {
        animation: blob-float 20s infinite alternate;
    }

    @keyframes blob-float {
        0% {
            transform: translate(0, 0) scale(1);
        }

        33% {
            transform: translate(30px, -50px) scale(1.1);
        }

        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }

        100% {
            transform: translate(0, 0) scale(1);
        }
    }
</style>
@endsection

@section('content')
<section class="relative pt-40 pb-20 overflow-hidden bg-[#080B14]">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-gold/5 rounded-full blur-[150px] -translate-y-1/2 translate-x-1/4 blob-animate"></div>
    <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-blue-600/5 rounded-full blur-[120px] translate-y-1/4 -translate-x-1/4 blob-animate" style="animation-delay: -5s;"></div>

    <div class="container mx-auto px-6 relative z-10">
        <!-- Hero Section -->
        <div class="text-center max-w-4xl mx-auto mb-20" data-aos="fade-up">
            <div class="inline-flex items-center gap-2.5 px-5 py-2 rounded-full bg-white/5 border border-white/10 mb-8 backdrop-blur-3xl shadow-xl relative group overflow-hidden">
                <div class="absolute inset-0 shimmer opacity-50"></div>
                <span class="w-2 h-2 rounded-full bg-gold shadow-[0_0_10px_#FFD700] animate-pulse relative z-10"></span>
                <span class="text-white/90 text-[9px] font-black uppercase tracking-[0.4em] relative z-10">Standar Layanan Transportasi Terbaik</span>
            </div>
            <h1 class="font-poppins font-black text-5xl md:text-7xl text-white mb-8 leading-[1] tracking-tighter">
                Tentang <span class="text-gradient-gold drop-shadow-[0_0_40px_rgba(212,175,55,0.3)]">Kami</span>
            </h1>
            <p class="text-slate-400 text-lg md:text-xl leading-relaxed font-medium max-w-3xl mx-auto mb-12 opacity-90">
                Siliwangi Rental Trans Nusa berkomitmen menghadirkan pengalaman perjalanan yang aman, nyaman, dan menyenangkan di setiap perjalanan Anda.
            </p>
        </div>

        <!-- Experience Section (Profil Kami) -->
        <div class="flex flex-col lg:flex-row gap-16 lg:gap-24 items-center mb-40">
            <div class="w-full lg:w-5/12 relative" data-aos="fade-right">
                <div class="aspect-[4/5] w-full rounded-[3.5rem] overflow-hidden border border-white/5 relative group shadow-xl">
                    <!-- Faux Image Placeholder with Premium Pattern -->
                    <div class="absolute inset-0 bg-[#0B0F1A] flex items-center justify-center overflow-hidden">
                        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
                        <i class="fas fa-gem text-white/[0.02] text-[20rem] rotate-12 group-hover:scale-110 group-hover:rotate-0 transition-transform duration-[2000ms]"></i>
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent"></div>
                    </div>

                    <div class="absolute inset-x-6 bottom-6">
                        <div class="glass-card p-8 border-gold/20 bg-slate-950/80 backdrop-blur-3xl rounded-[2.5rem] shadow-xl">
                            <div class="flex items-center gap-4 mb-5">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gold/30 to-gold/10 text-gold flex items-center justify-center border border-gold/30 shadow-xl">
                                    <i class="fas fa-map-marker-alt text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-white font-black text-lg tracking-tight uppercase">Operational Hub</h3>
                                    <p class="text-gold text-[9px] font-black uppercase tracking-[0.3em]">Bandung, Jawa Barat</p>
                                </div>
                            </div>
                            <p class="text-slate-400 text-xs leading-relaxed font-medium">Layanan armada terintegrasi untuk menjamin ketersediaan unit tepat waktu di berbagai lokasi.</p>
                        </div>
                    </div>
                </div>
                <!-- Decorative element -->
                <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-gold/10 rounded-full blur-[60px] -z-10"></div>
            </div>

            <div class="w-full lg:w-7/12" data-aos="fade-left">
                <div class="inline-flex items-center gap-3 px-5 py-2 rounded-xl bg-gold/5 border border-gold/10 mb-8 backdrop-blur-xl">
                    <span class="w-2 h-2 rounded-full bg-gold shadow-[0_0_10px_#FFD700]"></span>
                    <span class="text-gold text-[10px] font-black uppercase tracking-[0.2em]">Siliwangi Rental Trans Nusa</span>
                </div>

                <h2 class="font-poppins font-black text-4xl md:text-5xl text-white mb-8 leading-[1.1] tracking-tighter">
                    Solusi Transportasi <br> Terpercaya Anda
                </h2>
                
                <div class="space-y-6 text-slate-400 text-base md:text-lg leading-relaxed font-medium opacity-90 max-w-2xl">
                    <p>
                        <strong>Siliwangi Rental Trans Nusa</strong> adalah perusahaan penyedia jasa rental mobil yang berkomitmen memberikan layanan transportasi yang aman, nyaman, dan terpercaya untuk berbagai kebutuhan perjalanan. Dengan dukungan armada yang terawat serta pelayanan profesional, kami hadir sebagai solusi transportasi bagi individu, keluarga, perusahaan, maupun instansi.
                    </p>
                    <p>
                        Kami memahami bahwa setiap perjalanan memiliki kebutuhan yang berbeda. Oleh karena itu, kami menyediakan berbagai pilihan kendaraan mulai dari city car, MPV, SUV, hingga kendaraan premium yang dapat disesuaikan dengan kebutuhan pelanggan.
                    </p>
                    <p>
                        Selain layanan <strong>sewa mobil lepas kunci</strong>, kami juga menyediakan layanan <strong>sewa mobil dengan driver profesional</strong> yang berpengalaman dan mengutamakan keselamatan serta kenyamanan selama perjalanan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="py-32 bg-[#060912] overflow-hidden relative border-y border-white/5">
    <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col lg:flex-row gap-12 items-stretch">
            <!-- Vision -->
            <div class="w-full lg:w-1/2 glass-card glass-card-hover p-12 rounded-[2.5rem] relative overflow-hidden group shadow-xl" data-aos="fade-up">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-gold/5 rounded-full blur-[100px] transition-all group-hover:bg-gold/10"></div>
                <div class="w-16 h-16 rounded-2xl bg-gold/10 text-gold flex items-center justify-center text-2xl mb-10 border border-gold/20 shadow-inner">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 class="text-white font-black text-3xl mb-6 tracking-tighter uppercase">Visi</h3>
                <p class="text-slate-400 leading-relaxed text-lg font-medium opacity-85">
                    Menjadi perusahaan rental mobil terpercaya yang memberikan layanan transportasi berkualitas, profesional, dan berorientasi pada kepuasan pelanggan.
                </p>
            </div>

            <!-- Mission -->
            <div class="w-full lg:w-1/2 glass-card glass-card-hover p-12 rounded-[2.5rem] relative overflow-hidden group shadow-xl" data-aos="fade-up" data-aos-delay="200">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-600/5 rounded-full blur-[100px] transition-all group-hover:bg-blue-600/10"></div>
                <div class="w-16 h-16 rounded-2xl bg-blue-600/10 text-blue-400 flex items-center justify-center text-2xl mb-10 border border-blue-600/20 shadow-inner">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <h3 class="text-white font-black text-3xl mb-6 tracking-tighter uppercase">Misi</h3>
                <ul class="space-y-4">
                    @foreach([
                    'Menyediakan armada kendaraan yang aman, bersih, dan terawat.',
                    'Memberikan pelayanan yang cepat, ramah, dan profesional.',
                    'Menawarkan solusi transportasi yang fleksibel sesuai kebutuhan pelanggan.',
                    'Menjalin hubungan jangka panjang dengan pelanggan melalui pelayanan terbaik.',
                    'Mengembangkan layanan secara berkelanjutan dengan memanfaatkan teknologi informasi.'
                    ] as $misi)
                    <li class="flex items-start gap-4 text-slate-400 text-base font-medium group/li">
                        <div class="w-6 h-6 rounded-full bg-gold/10 flex items-center justify-center shrink-0 mt-0.5 group-hover/li:bg-gold transition-colors">
                            <i class="fas fa-check text-gold text-[10px] group-hover/li:text-slate-950 transition-colors"></i>
                        </div>
                        <span class="group-hover/li:text-white transition-colors">{{ $misi }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Mengapa Memilih Kami Section -->
<section class="py-32 bg-[#080B14] overflow-hidden relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center max-w-2xl mx-auto mb-20" data-aos="fade-up">
            <span class="inline-block px-5 py-2 rounded-full bg-gold/10 text-gold text-[9px] font-black uppercase tracking-[0.4em] mb-6 border border-gold/20 backdrop-blur-xl">Keunggulan Layanan</span>
            <h2 class="font-poppins font-black text-4xl text-white tracking-tighter mb-4">Mengapa Memilih <span class="text-gold">Kami</span>?</h2>
            <p class="text-slate-500 text-base font-medium max-w-xl mx-auto">Keunggulan utama yang menjadikan Siliwangi Rental pilihan terbaik untuk perjalanan Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">
            @php $reasons = [
                ['title' => 'Armada Berkualitas', 'desc' => 'Seluruh kendaraan kami menjalani perawatan dan pemeriksaan rutin untuk memastikan keamanan dan kemudahan serta kenyamanan selama digunakan.', 'icon' => 'fas fa-car-side'],
                ['title' => 'Harga Kompetitif', 'desc' => 'Kami menawarkan harga sewa yang transparan dan kompetitif dengan kualitas layanan terbaik.', 'icon' => 'fas fa-tags'],
                ['title' => 'Pelayanan Profesional', 'desc' => 'Didukung oleh tim yang responsif dan berpengalaman dalam melayani kebutuhan transportasi pelanggan.', 'icon' => 'fas fa-user-tie'],
                ['title' => 'Driver Berpengalaman', 'desc' => 'Layanan dengan driver didukung oleh pengemudi yang profesional, ramah, dan memahami berbagai rute perjalanan.', 'icon' => 'fas fa-id-card'],
                ['title' => 'Proses Booking Mudah', 'desc' => 'Pemesanan kendaraan dapat dilakukan dengan cepat melalui website maupun layanan pelanggan kami.', 'icon' => 'fas fa-calendar-check']
            ]; @endphp

            @foreach($reasons as $index => $reason)
            <div class="glass-card p-8 rounded-[2rem] border-white/5 hover:border-gold/30 hover:bg-white/[0.04] transition-all duration-500 group relative overflow-hidden flex flex-col justify-between" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-gold/5 rounded-full blur-3xl group-hover:bg-gold/10 transition-all"></div>
                <div>
                    <div class="w-16 h-16 rounded-[1.2rem] bg-slate-900 border border-white/10 flex items-center justify-center text-gold text-2xl mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-lg">
                        <i class="{{ $reason['icon'] }}"></i>
                    </div>
                    <h3 class="text-white font-black text-xl mb-4 tracking-tight group-hover:text-gold transition-colors">{{ $reason['title'] }}</h3>
                    <p class="text-slate-400 leading-relaxed text-sm font-medium">{{ $reason['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Komitmen Kami Section -->
<section class="py-32 bg-[#060912] border-t border-white/5 overflow-hidden relative">
    <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto glass-card p-12 md:p-16 rounded-[3rem] relative overflow-hidden group shadow-2xl border-gold/20" data-aos="zoom-in">
            <div class="absolute -left-20 top-0 w-64 h-64 bg-gold/5 rounded-full blur-[80px]"></div>
            <div class="absolute -right-20 bottom-0 w-64 h-64 bg-blue-500/5 rounded-full blur-[80px]"></div>
            
            <div class="relative z-10 text-center">
                <span class="inline-block px-5 py-2 rounded-full bg-gold/10 text-gold text-[9px] font-black uppercase tracking-[0.4em] mb-6 border border-gold/20">Komitmen Kami</span>
                <h2 class="font-poppins font-black text-3xl md:text-4xl text-white mb-6">Kepuasan Pelanggan Adalah Prioritas Utama</h2>
                <p class="text-slate-300 text-base md:text-lg leading-relaxed mb-8 max-w-3xl mx-auto opacity-95">
                    Siliwangi Rental Trans Nusa senantiasa berupaya memberikan pelayanan terbaik dengan menjaga kualitas armada, meningkatkan profesionalisme layanan, serta menghadirkan pengalaman perjalanan yang aman, nyaman, dan menyenangkan.
                </p>
                <p class="text-gold font-poppins font-bold text-lg">
                    Terima kasih atas kepercayaan Anda kepada Siliwangi Rental Trans Nusa. Kami siap menjadi partner perjalanan terbaik untuk setiap kebutuhan transportasi Anda.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Map & Location -->
<section class="py-32 bg-[#080B14] border-t border-white/5 relative overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col lg:flex-row gap-16 lg:gap-24 items-center">
            <div class="w-full lg:w-5/12" data-aos="fade-right">
                <span class="inline-block px-5 py-2 rounded-full bg-blue-600/10 text-blue-400 text-[9px] font-black uppercase tracking-[0.4em] mb-8 border border-blue-600/20">Kantor Pusat Kami</span>
                <h2 class="font-poppins font-black text-4xl text-white mb-8 leading-[1.1] tracking-tighter">Kunjungi <br> <span class="text-gradient-gold text-5xl drop-shadow-xl">Siliwangi HQ</span></h2>
                <p class="text-slate-400 text-lg mb-10 leading-relaxed font-medium opacity-80">Pusat kontrol operasional kami yang strategis untuk memastikan kecepatan pelayanan di seluruh wilayah Jawa Barat.</p>
                <div class="glass-card p-8 rounded-[2.5rem] border-white/5 bg-white/[0.01] relative group">
                    <div class="absolute inset-0 bg-gold/5 opacity-0 group-hover:opacity-100 transition-opacity rounded-[2.5rem]"></div>
                    <div class="flex items-start gap-6 relative z-10">
                        <div class="w-16 h-16 rounded-2xl bg-gold/10 flex items-center justify-center text-gold text-2xl border border-gold/20 shadow-xl shrink-0"><i class="fas fa-map-marked-alt"></i></div>
                        <div>
                            <div class="text-white font-black text-base uppercase tracking-widest mb-2">Alamat Kantor</div>
                            <p class="text-slate-400 text-base leading-relaxed font-medium">Jl. Siliwangi No. 88, <br> Kota Bandung, Jawa Barat</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-7/12 aspect-[16/10] rounded-[3.5rem] overflow-hidden border border-white/10 shadow-2xl relative" data-aos="fade-left">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.3540779394!2d107.5308957!3d-6.903446!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e9adf177bf8d%3A0x437398556c9d6de!2sBandung%2C%20Kota%20Bandung%2C%20Jawa%20Barat!5e0!3m2!1sid!2sid!4v1680000000000!5m2!1sid!2sid"
                    width="100%" height="100%" style="border:0;filter: grayscale(1) contrast(1.5) invert(0.9) brightness(0.7) sepia(0.2);" allowfullscreen="" loading="lazy"></iframe>
                <div class="absolute inset-0 pointer-events-none border-[20px] border-[#080B14]/80"></div>
                <!-- Interactive Map Pin Decor -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                    <div class="w-10 h-10 bg-gold rounded-full blur-xl animate-ping opacity-50"></div>
                    <div class="w-5 h-5 bg-gold rounded-full border-4 border-white shadow-xl relative z-10"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection