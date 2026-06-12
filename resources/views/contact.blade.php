@extends('layouts.app')
@section('title', 'Hubungi Kami – Siliwangi Rental')
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
                <span class="text-white/90 text-[9px] font-black uppercase tracking-[0.4em] relative z-10">Layanan Pelanggan Responsif</span>
            </div>
            <h1 class="font-poppins font-black text-5xl md:text-7xl text-white mb-8 leading-[1] tracking-tighter">
                Hubungi <span class="text-gradient-gold drop-shadow-[0_0_40px_rgba(212,175,55,0.3)]">Kami</span>
            </h1>
            <p class="text-slate-400 text-lg md:text-xl leading-relaxed font-medium max-w-3xl mx-auto opacity-90">
                Kami siap membantu kebutuhan transportasi Anda dengan pelayanan yang cepat, ramah, dan profesional. Jangan ragu untuk menghubungi kami untuk informasi ketersediaan armada, konsultasi kendaraan, maupun pemesanan rental mobil.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start mb-20">
            <!-- Left Column: Contact Details, Operational Hours, & Services -->
            <div class="lg:col-span-5 space-y-8" data-aos="fade-right">
                <!-- Contact Info Card -->
                <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group shadow-xl border-white/5">
                    <h3 class="text-white font-black text-2xl mb-6 tracking-tight uppercase border-b border-white/5 pb-4">
                        Informasi <span class="text-gold">Kontak</span>
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- Alamat -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gold/10 text-gold flex items-center justify-center text-lg border border-gold/20 shrink-0 shadow-lg">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h4 class="text-slate-500 text-[10px] font-black uppercase tracking-wider mb-1">Alamat Kantor</h4>
                                <p class="text-white font-bold text-sm leading-relaxed">Jl. Siliwangi No. XX, Kota Tasikmalaya, Jawa Barat, Indonesia</p>
                            </div>
                        </div>

                        <!-- WhatsApp / Telepon -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gold/10 text-gold flex items-center justify-center text-lg border border-gold/20 shrink-0 shadow-lg">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div>
                                <h4 class="text-slate-500 text-[10px] font-black uppercase tracking-wider mb-1">Telepon / WhatsApp</h4>
                                <p class="text-white font-bold text-sm leading-relaxed">+62 XXX-XXXX-XXXX</p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gold/10 text-gold flex items-center justify-center text-lg border border-gold/20 shrink-0 shadow-lg">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h4 class="text-slate-500 text-[10px] font-black uppercase tracking-wider mb-1">Email Resmi</h4>
                                <a href="mailto:info@siliwangirental.com" class="text-white font-bold text-sm leading-relaxed hover:text-gold transition-colors">info@siliwangirental.com</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Operational Hours Card -->
                <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group shadow-xl border-white/5">
                    <h3 class="text-white font-black text-2xl mb-6 tracking-tight uppercase border-b border-white/5 pb-4">
                        Jam <span class="text-gold">Operasional</span>
                    </h3>
                    <div class="overflow-hidden rounded-2xl border border-white/5">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white/5 text-slate-300 text-[10px] font-black uppercase tracking-wider">
                                    <th class="px-6 py-4">Hari</th>
                                    <th class="px-6 py-4">Jam Kerja</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-300 font-medium text-sm">
                                <tr class="border-b border-white/5 bg-white/[0.01]">
                                    <td class="px-6 py-4 font-semibold text-white">Senin - Jumat</td>
                                    <td class="px-6 py-4">08.00 - 21.00 WIB</td>
                                </tr>
                                <tr class="border-b border-white/5">
                                    <td class="px-6 py-4 font-semibold text-white">Sabtu</td>
                                    <td class="px-6 py-4">08.00 - 21.00 WIB</td>
                                </tr>
                                <tr class="bg-white/[0.01]">
                                    <td class="px-6 py-4 font-semibold text-white">Minggu</td>
                                    <td class="px-6 py-4">08.00 - 18.00 WIB</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Services List Card -->
                <div class="glass-card p-8 rounded-[2.5rem] relative overflow-hidden group shadow-xl border-white/5">
                    <h3 class="text-white font-black text-2xl mb-6 tracking-tight uppercase border-b border-white/5 pb-4">
                        Layanan <span class="text-gold">Kami</span>
                    </h3>
                    <ul class="space-y-4">
                        @foreach([
                            'Sewa Mobil Lepas Kunci',
                            'Sewa Mobil Dengan Driver',
                            'Rental Harian, Mingguan, dan Bulanan',
                            'Rental Kendaraan Perusahaan',
                            'Antar Jemput Bandara dan Hotel',
                            'Perjalanan Wisata dan Dinas'
                        ] as $layanan)
                        <li class="flex items-start gap-4 text-slate-300 text-sm font-medium group/item">
                            <div class="w-6 h-6 rounded-full bg-gold/10 flex items-center justify-center shrink-0 mt-0.5 group-hover/item:bg-gold transition-colors">
                                <i class="fas fa-check text-gold text-[10px] group-hover/item:text-slate-950 transition-colors"></i>
                            </div>
                            <span class="group-hover/item:text-white transition-colors">{{ $layanan }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Right Column: Message Form (Kirim Pesan) -->
            <div class="lg:col-span-7" data-aos="fade-left">
                <div class="glass-card p-10 md:p-12 rounded-[3.5rem] border-white/5 relative overflow-hidden shadow-2xl">
                    <div class="absolute -top-20 -right-20 w-60 h-60 bg-gold/5 rounded-full blur-[80px]"></div>
                    
                    <div class="relative z-10">
                        <h3 class="text-white font-poppins font-black text-3xl mb-4 uppercase">
                            Kirim <span class="text-gradient-gold">Pesan</span>
                        </h3>
                        <p class="text-slate-400 text-sm md:text-base leading-relaxed mb-8 font-medium">
                            Butuh kendaraan untuk perjalanan Anda? Silakan hubungi tim <strong>Siliwangi Rental Trans Nusa</strong> melalui WhatsApp, telepon, atau email. Tim kami akan membantu Anda menemukan armada yang sesuai dengan kebutuhan serta memberikan penawaran terbaik.
                        </p>

                        @if(session('success'))
                        <div class="mb-8 p-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center gap-4">
                            <i class="fas fa-check-circle text-xl"></i>
                            <span class="font-bold text-sm">{{ session('success') }}</span>
                        </div>
                        @endif

                        <form action="{{ url('/contact') }}" method="POST" class="space-y-8">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                    <div class="relative group">
                                        <input type="text" name="name" required class="w-full bg-[#080B14]/60 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold focus:ring-1 focus:ring-gold/50 outline-none transition-all text-sm" placeholder="Nama Anda">
                                        <i class="fas fa-user absolute right-6 top-1/2 -translate-y-1/2 text-slate-700 group-focus-within:text-gold transition-colors text-xs"></i>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor WhatsApp</label>
                                    <div class="relative group">
                                        <input type="tel" name="phone" required class="w-full bg-[#080B14]/60 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold focus:ring-1 focus:ring-gold/50 outline-none transition-all text-sm" placeholder="08xx-xxxx-xxxx">
                                        <i class="fab fa-whatsapp absolute right-6 top-1/2 -translate-y-1/2 text-slate-700 group-focus-within:text-gold transition-colors text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori Pertanyaan</label>
                                <select name="subject" class="w-full bg-[#080B14]/60 border border-white/10 rounded-2xl px-6 py-4 text-slate-300 focus:border-gold focus:ring-1 focus:ring-gold/50 outline-none transition-all text-sm appearance-none cursor-pointer">
                                    <option value="">Pilih Kategori</option>
                                    <option>Informasi Rental Kendaraan</option>
                                    <option>Kerjasama & Bisnis (B2B)</option>
                                    <option>Bantuan Teknis</option>
                                    <option>Lainnya</option>
                                </select>
                            </div>

                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pesan Anda</label>
                                <textarea name="message" required rows="5" class="w-full bg-[#080B14]/60 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold focus:ring-1 focus:ring-gold/50 outline-none transition-all text-sm" placeholder="Tuliskan pertanyaan atau pesan Anda di sini..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-5 rounded-2xl bg-gold text-slate-900 font-black text-xs uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-gold/20 flex items-center justify-center gap-3">
                                <i class="fas fa-paper-plane"></i> Kirim Pesan Sekarang
                            </button>
                        </form>

                        <div class="mt-12 pt-8 border-t border-white/5 text-center">
                            <p class="text-gold font-poppins font-bold text-base mb-2">
                                Siap Melayani Perjalanan Anda dengan Aman, Nyaman, dan Terpercaya.
                            </p>
                            <p class="text-slate-400 text-xs font-semibold">
                                <strong>Siliwangi Rental Trans Nusa</strong><br>
                                Partner Transportasi Terpercaya untuk Setiap Perjalanan Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
