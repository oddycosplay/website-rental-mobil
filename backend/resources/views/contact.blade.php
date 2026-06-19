@extends('layouts.app')
@section('title', 'Hubungi Kami – Siliwangi Rental')
@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.02);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    
    /* Leaflet Premium Style */
    .leaflet-popup-content-wrapper, .leaflet-popup-tip {
        background: rgba(15, 23, 42, 0.95) !important;
        backdrop-filter: blur(16px);
        border: 1px solid rgba(212, 175, 55, 0.3);
        color: #fff !important;
        border-radius: 1.5rem;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.5);
    }
    .leaflet-popup-content {
        margin: 16px 20px !important;
        font-family: 'Inter', sans-serif;
    }
    .leaflet-control-zoom-in, .leaflet-control-zoom-out {
        background: #0f172a !important;
        color: #fff !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    .leaflet-control-zoom-in:hover, .leaflet-control-zoom-out:hover {
        background: #D4AF37 !important;
        color: #000 !important;
    }
    .store-card.active-store {
        border-color: rgba(212, 175, 55, 0.5) !important;
        background: rgba(212, 175, 55, 0.05) !important;
        box-shadow: 0 10px 15px -3px rgba(212, 175, 55, 0.1);
    }
    .store-card.active-store .w-10 {
        background: #D4AF37 !important;
        color: #000 !important;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.01);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(212, 175, 55, 0.3);
        border-radius: 2px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(212, 175, 55, 0.5);
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
                        @foreach($stores as $store)
                        <div class="border-b border-white/5 pb-6 last:border-b-0 last:pb-0">
                            <h4 class="text-gold text-xs font-black uppercase tracking-wider mb-3 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-gold animate-pulse"></span>
                                {{ $store->name }}
                            </h4>
                            <div class="space-y-3 pl-3.5">
                                <!-- Alamat -->
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-map-marker-alt text-gold/60 text-xs mt-0.5 shrink-0"></i>
                                    <div>
                                        <p class="text-slate-300 font-medium text-xs leading-relaxed">{{ $store->address }}, {{ $store->city }}, {{ $store->province }}</p>
                                    </div>
                                </div>

                                <!-- WhatsApp / Telepon -->
                                @if($store->phone)
                                <div class="flex items-start gap-3">
                                    <i class="fab fa-whatsapp text-gold/60 text-xs mt-0.5 shrink-0"></i>
                                    <div>
                                        <p class="text-slate-300 font-medium text-xs leading-relaxed">{{ $store->phone }}</p>
                                    </div>
                                </div>
                                @endif

                                <!-- Email -->
                                @if($store->email)
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-envelope text-gold/60 text-xs mt-0.5 shrink-0"></i>
                                    <div>
                                        <a href="mailto:{{ $store->email }}" class="text-slate-300 font-medium text-xs leading-relaxed hover:text-gold transition-colors">{{ $store->email }}</a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
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

        <!-- Peta Lokasi Cabang Interaktif -->
        <div class="mt-24" data-aos="fade-up">
            <div class="glass-card p-8 md:p-12 rounded-[3.5rem] border-white/5 relative overflow-hidden shadow-2xl">
                <div class="absolute -top-20 -left-20 w-60 h-60 bg-gold/5 rounded-full blur-[80px]"></div>
                
                <div class="relative z-10 flex flex-col lg:flex-row gap-12 items-stretch">
                    <!-- Left: Branch List -->
                    <div class="w-full lg:w-4/12 flex flex-col justify-between">
                        <div>
                            <span class="inline-flex items-center gap-2.5 px-5 py-2 rounded-full bg-white/5 border border-white/10 mb-6 backdrop-blur-3xl shadow-xl relative group overflow-hidden">
                                <span class="w-2 h-2 rounded-full bg-gold shadow-[0_0_10px_#FFD700] animate-pulse"></span>
                                <span class="text-white/90 text-[9px] font-black uppercase tracking-[0.4em]">Peta Interaktif</span>
                            </span>
                            <h3 class="text-white font-poppins font-black text-3xl mb-4 uppercase">
                                Lokasi <span class="text-gradient-gold">Cabang</span>
                            </h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-8 font-medium">
                                Klik pada daftar cabang di bawah ini untuk mengarahkan peta ke lokasi cabang dan menampilkan informasi detail kontak.
                            </p>
                        </div>
                        
                        <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($stores as $index => $store)
                            <div class="store-card cursor-pointer glass-card p-5 rounded-2xl border-white/5 bg-white/[0.01] hover:border-gold/30 hover:bg-white/[0.03] transition-all relative group" 
                                 data-store-id="{{ $store->id }}"
                                 data-index="{{ $index }}">
                                <div class="absolute inset-0 bg-gold/[0.02] opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
                                <div class="flex items-start gap-4 relative z-10">
                                    <div class="w-10 h-10 rounded-lg bg-gold/10 flex items-center justify-center text-gold text-lg border border-gold/20 shadow-md shrink-0 transition-colors">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-white font-black text-xs uppercase tracking-wider mb-1">{{ $store->name }}</div>
                                        <p class="text-slate-400 text-[11px] leading-relaxed mb-2">{{ $store->address }}, {{ $store->city }}</p>
                                        <div class="flex flex-wrap gap-x-3 gap-y-1 text-[9px] text-slate-500 font-medium">
                                            @if($store->phone)
                                            <span><i class="fas fa-phone-alt text-gold/60 mr-1 flex-shrink-0"></i>{{ $store->phone }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Right: Leaflet Map -->
                    <div class="w-full lg:w-8/12 min-h-[450px] rounded-[2.5rem] overflow-hidden border border-white/10 shadow-2xl relative">
                        <div id="map" class="w-full h-full min-h-[450px] z-10"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stores = @json($stores);
        
        // Parse koordinat
        stores.forEach(store => {
            if (store.google_maps) {
                const coords = store.google_maps.split(',').map(c => parseFloat(c.trim()));
                if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
                    store.lat = coords[0];
                    store.lng = coords[1];
                }
            }
            // Fallback koordinat default jika kosong
            if (!store.lat || !store.lng) {
                if (store.city && store.city.toLowerCase().includes('bandung')) {
                    store.lat = -6.921876;
                    store.lng = 107.611116;
                } else {
                    store.lat = -6.211548;
                    store.lng = 106.822989;
                }
            }
        });

        // Inisialisasi Peta
        const defaultCenter = stores.length > 0 ? [stores[0].lat, stores[0].lng] : [-6.921876, 107.611116];
        const map = L.map('map', {
            zoomControl: true,
            scrollWheelZoom: false
        }).setView(defaultCenter, 12);

        // Tile layer CartoDB Dark Matter
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        // Custom Gold Icon
        const goldIcon = L.divIcon({
            html: `<div class="relative flex items-center justify-center">
                    <div class="absolute w-8 h-8 bg-gold/30 rounded-full animate-ping opacity-60"></div>
                    <div class="absolute w-4 h-4 bg-gold rounded-full border-2 border-white shadow-lg z-10"></div>
                   </div>`,
            className: '',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        const markers = {};

        // Tambah marker ke peta
        stores.forEach((store, idx) => {
            const popupContent = `
                <div class="text-xs">
                    <div class="font-poppins font-black text-gold uppercase tracking-wider mb-1">${store.name}</div>
                    <div class="text-slate-300 font-medium mb-2">${store.address}</div>
                    <div class="text-[10px] text-slate-400">
                        ${store.phone ? `<div><i class="fas fa-phone-alt text-gold mr-1"></i> ${store.phone}</div>` : ''}
                        ${store.email ? `<div><i class="fas fa-envelope text-gold mr-1"></i> ${store.email}</div>` : ''}
                    </div>
                </div>
            `;

            const marker = L.marker([store.lat, store.lng], { icon: goldIcon })
                .addTo(map)
                .bindPopup(popupContent);

            markers[store.id] = marker;

            // Interaksi saat marker diklik
            marker.on('click', () => {
                highlightStoreCard(store.id);
            });
        });

        // Event listener saat card cabang diklik
        const storeCards = document.querySelectorAll('.store-card');
        storeCards.forEach(card => {
            card.addEventListener('click', function() {
                const storeId = this.getAttribute('data-store-id');
                const index = this.getAttribute('data-index');
                const store = stores[index];

                highlightStoreCard(storeId);

                // Pan map ke lokasi
                map.setView([store.lat, store.lng], 15, {
                    animate: true,
                    duration: 1.2
                });

                // Buka popup marker
                if (markers[storeId]) {
                    markers[storeId].openPopup();
                }
            });
        });

        // Fungsi highlight store card aktif
        function highlightStoreCard(storeId) {
            storeCards.forEach(card => {
                if (card.getAttribute('data-store-id') == storeId) {
                    card.classList.add('active-store');
                    card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                } else {
                    card.classList.remove('active-store');
                }
            });
        }

        // Highlight toko pertama secara default jika ada
        if (stores.length > 0) {
            highlightStoreCard(stores[0].id);
            if (markers[stores[0].id]) {
                setTimeout(() => {
                    markers[stores[0].id].openPopup();
                }, 500);
            }
        }
    });
</script>
@endsection
