@extends('layouts.app')
@section('title', 'FAQ – Siliwangi Rental')
@section('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.02);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .glass-faq {
        background: rgba(255, 255, 255, 0.02);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 1.5rem;
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
@php 
    $faqs = [
        ['fas fa-clipboard-list', 'Pemesanan & Verifikasi', [
            ['Bagaimana cara melakukan pemesanan kendaraan?', 'Anda dapat melakukan pemesanan langsung melalui website dengan memilih kendaraan yang tersedia, menentukan tanggal sewa, mengisi data diri, dan menyelesaikan proses pembayaran. Tim kami juga siap membantu pemesanan melalui WhatsApp.'],
            ['Dokumen apa saja yang diperlukan untuk sewa mobil lepas kunci?', 'Umumnya pelanggan perlu menyiapkan: KTP yang masih berlaku, SIM A yang masih berlaku, informasi kontak yang dapat dihubungi, dan dokumen pendukung lainnya apabila diperlukan dalam proses verifikasi.'],
            ['Bagaimana cara mengetahui ketersediaan armada?', 'Anda dapat melihat ketersediaan armada melalui website atau menghubungi tim kami melalui WhatsApp untuk mendapatkan informasi terbaru mengenai kendaraan yang tersedia.'],
            ['Berapa lama proses verifikasi booking?', 'Proses verifikasi biasanya dilakukan dalam waktu singkat setelah data dan dokumen pelanggan diterima. Waktu verifikasi dapat berbeda tergantung jenis layanan yang dipilih.']
        ]],
        ['fas fa-car', 'Layanan & Armada', [
            ['Apakah tersedia layanan sewa mobil lepas kunci?', 'Ya. Siliwangi Rental Trans Nusa menyediakan layanan sewa mobil lepas kunci untuk pelanggan yang memenuhi persyaratan dan verifikasi yang ditentukan.'],
            ['Apakah tersedia layanan sewa mobil dengan driver?', 'Ya. Kami menyediakan layanan sewa mobil lengkap dengan driver profesional yang berpengalaman untuk kebutuhan wisata, perjalanan dinas, acara keluarga, maupun operasional perusahaan.'],
            ['Apakah tersedia penyewaan bulanan atau kontrak perusahaan?', 'Ya. Kami melayani penyewaan harian, mingguan, bulanan, hingga kontrak jangka panjang untuk perusahaan, instansi, maupun proyek tertentu. Silakan hubungi tim kami untuk mendapatkan penawaran khusus.'],
            ['Apakah kendaraan dapat diantar ke lokasi pelanggan?', 'Ya. Kami menyediakan layanan antar dan jemput kendaraan sesuai area layanan yang tersedia. Biaya tambahan dapat berlaku tergantung lokasi pengantaran.'],
            ['Apakah ada batasan wilayah penggunaan kendaraan?', 'Kendaraan dapat digunakan sesuai dengan ketentuan yang berlaku pada saat penyewaan. Jika perjalanan dilakukan ke luar kota atau untuk kebutuhan khusus, harap informasikan kepada tim kami terlebih dahulu.'],
            ['Apakah tersedia kendaraan premium untuk acara khusus?', 'Ya. Kami menyediakan berbagai kendaraan premium untuk kebutuhan perjalanan bisnis, tamu VIP, acara pernikahan, maupun kegiatan khusus lainnya.']
        ]],
        ['fas fa-credit-card', 'Pembayaran & Kebijakan', [
            ['Metode pembayaran apa yang tersedia?', 'Pembayaran dapat dilakukan melalui berbagai metode yang tersedia pada sistem pembayaran, seperti transfer bank, virtual account, e-wallet, QRIS, dan metode pembayaran lain yang didukung oleh payment gateway.'],
            ['Apakah saya bisa membatalkan pesanan?', 'Ya. Pembatalan pesanan dapat dilakukan sesuai dengan ketentuan dan kebijakan pembatalan yang berlaku. Jika terdapat pembayaran yang memenuhi syarat refund, proses pengembalian dana akan diproses oleh tim kami.'],
            ['Apakah kendaraan diasuransikan?', 'Kami selalu mengutamakan keamanan dan kenyamanan pelanggan. Informasi mengenai perlindungan kendaraan dan ketentuan asuransi dapat dikonsultasikan langsung dengan tim kami saat melakukan pemesanan.'],
            ['Bagaimana jika kendaraan mengalami kerusakan atau kendala selama masa sewa?', 'Segera hubungi tim Siliwangi Rental Trans Nusa melalui nomor layanan pelanggan. Tim kami akan memberikan bantuan dan solusi sesuai dengan kondisi yang terjadi.'],
            ['Bagaimana cara menghubungi layanan pelanggan?', 'Anda dapat menghubungi kami melalui: WhatsApp, Telepon, Email, atau Formulir kontak pada website. Tim Siliwangi Rental Trans Nusa siap membantu kebutuhan transportasi Anda dengan cepat dan profesional.']
        ]]
    ];
@endphp

<div x-data="{ 
    search: '',
    faqs: [
        @foreach($faqs as $cat)
            @foreach($cat[2] as $qa)
                { q: '{{ str_replace("'", "\\'", $qa[0]) }}', a: '{{ str_replace("'", "\\'", $qa[1]) }}', cat: '{{ $cat[1] }}' },
            @endforeach
        @endforeach
    ],
    get filteredFaqs() {
        if (!this.search) return this.faqs;
        return this.faqs.filter(f => 
            f.q.toLowerCase().includes(this.search.toLowerCase()) || 
            f.a.toLowerCase().includes(this.search.toLowerCase())
        );
    }
}">
<section class="relative pt-40 pb-20 overflow-hidden bg-[#080B14]">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-gold/5 rounded-full blur-[150px] -translate-y-1/2 translate-x-1/4 blob-animate"></div>
    <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-blue-600/5 rounded-full blur-[120px] translate-y-1/4 -translate-x-1/4 blob-animate" style="animation-delay: -5s;"></div>

    <div class="container mx-auto px-6 relative z-10 text-center max-w-3xl">
        <div class="inline-flex items-center gap-2.5 px-5 py-2 rounded-full bg-white/5 border border-white/10 mb-8 backdrop-blur-3xl shadow-xl relative group overflow-hidden">
            <div class="absolute inset-0 shimmer opacity-50"></div>
            <span class="w-2 h-2 rounded-full bg-gold shadow-[0_0_10px_#FFD700] animate-pulse relative z-10"></span>
            <span class="text-white/90 text-[9px] font-black uppercase tracking-[0.4em] relative z-10">Pusat Bantuan & FAQ</span>
        </div>
        <h1 class="font-poppins font-black text-5xl md:text-7xl text-white mb-8 leading-[1] tracking-tighter">
            Pertanyaan Umum <span class="text-gradient-gold drop-shadow-[0_0_40px_rgba(212,175,55,0.3)]">FAQ</span>
        </h1>
        <p class="text-slate-400 text-lg md:text-xl leading-relaxed font-medium mb-12 opacity-90">
            Temukan jawaban cepat untuk pertanyaan yang sering diajukan mengenai proses pemesanan, pembayaran, dan layanan kami.
        </p>
        
        <div class="relative max-w-xl mx-auto" data-aos="fade-up">
            <input type="text" x-model="search" placeholder="Cari pertanyaan..." class="w-full bg-[#080B14]/60 border border-white/10 rounded-2xl px-8 py-5 text-white focus:border-gold focus:ring-1 focus:ring-gold/50 outline-none transition-all pr-16 backdrop-blur-xl text-sm">
            <div class="absolute right-6 top-1/2 -translate-y-1/2 text-gold">
                <i class="fas fa-search"></i>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-[#080B14] relative">
    <div class="container mx-auto px-6 max-w-4xl">
        <!-- Search Results -->
        <template x-if="search">
            <div class="mb-20">
                <div class="flex items-center gap-4 mb-8 px-4">
                    <h2 class="text-white font-black text-xl uppercase tracking-widest">Hasil Pencarian</h2>
                    <div class="flex-1 h-[1px] bg-white/5"></div>
                </div>
                <div class="space-y-4">
                    <template x-for="(f, i) in filteredFaqs" :key="i">
                        <div class="glass-faq overflow-hidden transition-all duration-300 border-gold/30 bg-gold/5">
                            <div class="px-8 py-6">
                                <span class="text-[8px] font-black text-gold uppercase tracking-widest mb-2 block" x-text="f.cat"></span>
                                <h3 class="text-white font-bold text-lg mb-4" x-text="f.q"></h3>
                                <p class="text-slate-400 text-base leading-relaxed pt-4 border-t border-white/5" x-text="f.a"></p>
                            </div>
                        </div>
                    </template>
                    <template x-if="filteredFaqs.length === 0">
                        <div class="text-center py-12 glass-faq">
                            <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">Pertanyaan tidak ditemukan.</p>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        <!-- Static FAQ List (hidden when searching) -->
        <div x-show="!search" class="space-y-12">
            @foreach($faqs as $catIndex => $cat)
            <div data-aos="fade-up" data-aos-delay="{{ $catIndex * 100 }}">
                <div class="flex items-center gap-4 mb-8 px-4">
                    <div class="w-10 h-10 rounded-xl bg-gold/10 text-gold flex items-center justify-center border border-gold/10 text-sm">
                        <i class="{{ $cat[0] }}"></i>
                    </div>
                    <h2 class="text-white font-black text-xl uppercase tracking-widest">{{ $cat[1] }}</h2>
                    <div class="flex-1 h-[1px] bg-white/5"></div>
                </div>

                <div class="space-y-4" x-data="{ active: null }">
                    @foreach($cat[2] as $i => $qa)
                    <div class="glass-faq overflow-hidden transition-all duration-300" 
                         :class="{ 'border-gold/30 bg-gold/5': active === {{ $i }} }">
                         <button 
                            @click="active = active === {{ $i }} ? null : {{ $i }}"
                            class="w-full text-left px-8 py-6 flex items-center justify-between group">
                            <span class="text-white font-bold text-base md:text-lg group-hover:text-gold transition-colors pr-8">{{ $qa[0] }}</span>
                            <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-500 transition-all duration-500 shrink-0"
                                 :class="{ 'rotate-180 text-gold bg-gold/20': active === {{ $i }} }">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </button>
                        <div 
                            x-show="active === {{ $i }}" 
                            x-collapse
                            x-cloak>
                            <div class="px-8 pb-8 text-slate-400 text-sm md:text-base leading-relaxed border-t border-white/5 pt-6">
                                {{ $qa[1] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <!-- CTA Box -->
        <div class="mt-32 p-12 glass-faq border-gold/20 text-center relative overflow-hidden" data-aos="zoom-in">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-gold/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-500/5 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <h3 class="text-white font-black text-3xl mb-4">Masih Memiliki Pertanyaan?</h3>
                <p class="text-slate-400 text-base mb-10 max-w-xl mx-auto">Jika Anda tidak menemukan jawaban yang Anda cari, tim Siliwangi Rental Trans Nusa siap membantu Anda secara cepat dan profesional.</p>
                <a href="https://wa.me/628973716530" target="_blank" class="inline-flex items-center gap-3 px-10 py-5 rounded-2xl bg-gold text-slate-900 font-black text-xs uppercase tracking-widest hover:scale-105 transition-all shadow-xl shadow-gold/20">
                    <i class="fab fa-whatsapp text-lg"></i> Hubungi Customer Service
                </a>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
