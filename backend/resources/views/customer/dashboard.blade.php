@extends('layouts.app')

@section('title', 'Dashboard Saya – Siliwangi Rental')

@section('content')
<div class="pt-28 pb-20 bg-slate-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-6">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12" data-aos="fade-down">
            <div>
                <h1 class="font-poppins text-4xl font-black text-white">Dashboard <span class="text-gold">Saya</span></h1>
                <p class="text-slate-500 font-medium mt-1 uppercase tracking-widest text-[10px]">Welcome back, {{ auth()->user()->name }}</p>
            </div>
            <a href="{{ route('cars.index') }}" class="px-8 py-4 rounded-2xl bg-gold text-slate-900 font-black text-sm hover:scale-105 active:scale-95 transition-all shadow-xl shadow-gold/20 uppercase tracking-widest flex items-center gap-3">
                <i class="fas fa-car-side"></i> Buat Sewa
            </a>
        </div>

        {{-- Stats Overview --}}
        @php
            $customer = auth()->user()->customer;
            $bookings = $customer ? \App\Models\Booking::where('customer_id', $customer->id)->with('car')->latest()->get() : collect();
            $activeCount = $bookings->whereIn('booking_status', ['confirmed', 'ongoing'])->count();
            $totalSpend = $bookings->where('booking_status', 'completed')->sum('grand_total');
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12" data-aos="fade-up">
            <div class="bg-slate-800/40 backdrop-blur-xl border border-white/5 rounded-[2rem] p-8 flex items-center gap-6 group hover:border-gold/30 transition-all">
                <div class="w-16 h-16 rounded-2xl bg-gold/10 flex items-center justify-center text-gold text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Pesanan</div>
                    <div class="text-3xl font-black text-white">{{ $bookings->count() }}</div>
                </div>
            </div>
            <div class="bg-slate-800/40 backdrop-blur-xl border border-white/5 rounded-[2rem] p-8 flex items-center gap-6 group hover:border-gold/30 transition-all">
                <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-route"></i>
                </div>
                <div>
                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Sewa Aktif</div>
                    <div class="text-3xl font-black text-white">{{ $activeCount }}</div>
                </div>
            </div>
            <div class="bg-slate-800/40 backdrop-blur-xl border border-white/5 rounded-[2rem] p-8 flex items-center gap-6 group hover:border-gold/30 transition-all">
                <div class="w-16 h-16 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-500 text-2xl group-hover:scale-110 transition-transform">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Pengeluaran</div>
                    <div class="text-2xl font-black text-white">Rp {{ number_format($totalSpend, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <!-- Profile & Documents -->
            <div class="lg:col-span-1 space-y-8" data-aos="fade-right">
                <div class="bg-slate-800/40 backdrop-blur-xl border border-white/5 rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden">
                    <div class="absolute -top-12 -right-12 w-32 h-32 bg-gold/5 rounded-full blur-2xl"></div>
                    
                    <h3 class="font-poppins font-black text-white text-xl mb-8 flex items-center gap-4">
                        <span class="w-10 h-10 rounded-xl bg-gold/10 flex items-center justify-center text-gold"><i class="fas fa-user-edit"></i></span>
                        Update Profil
                    </h3>

                    <livewire:customer-profile-editor />
                </div>

                <div class="bg-gold/5 border border-gold/10 rounded-[2rem] p-8" data-aos="fade-up" data-aos-delay="100">
                    <h4 class="font-black text-gold mb-3 flex items-center gap-3 text-xs uppercase tracking-widest">
                        <i class="fas fa-shield-check text-lg"></i> Verified Storage
                    </h4>
                    <p class="text-[11px] text-slate-500 font-bold leading-relaxed uppercase tracking-wider">
                        Data dan dokumen Anda disimpan dengan enkripsi militer dan hanya digunakan untuk verifikasi armada.
                    </p>
                </div>
            </div>

            <!-- Booking History -->
            <div class="lg:col-span-2" data-aos="fade-left">
                <div class="bg-slate-800/40 backdrop-blur-xl border border-white/5 rounded-[2.5rem] p-10 shadow-2xl min-h-[500px]">
                    <h3 class="font-poppins font-black text-white text-xl mb-8 flex items-center gap-4">
                        <span class="w-10 h-10 rounded-xl bg-gold/10 flex items-center justify-center text-gold"><i class="fas fa-list-ul"></i></span>
                        History Pemesanan
                    </h3>

                    <div class="overflow-x-auto rounded-2xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-white/5">
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Pesanan</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Armada</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Status</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-right">Total</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($bookings as $booking)
                                <tr class="hover:bg-white/5 transition-all group">
                                    <td class="px-6 py-6">
                                        <div class="text-sm font-black text-white mb-1 group-hover:text-gold transition-colors">#{{ $booking->booking_code }}</div>
                                        <div class="text-[10px] text-slate-600 font-bold uppercase tracking-widest">{{ $booking->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="text-sm text-slate-300 font-black">{{ $booking->car->car_name }}</div>
                                        <div class="text-[10px] text-slate-600 font-bold uppercase tracking-widest">{{ $booking->pickup_date->format('d M') }} - {{ $booking->return_date->format('d M') }}</div>
                                    </td>
                                    <td class="px-6 py-6">
                                        @php
                                            $statusStyle = [
                                                'pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                                'confirmed' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                                'ongoing' => 'bg-indigo-500/10 text-indigo-500 border-indigo-500/20',
                                                'completed' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                                'cancelled' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                            ][$booking->booking_status] ?? 'bg-slate-500/10 text-slate-500 border-slate-500/20';
                                        @endphp
                                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black border uppercase tracking-widest {{ $statusStyle }}">
                                            {{ $booking->booking_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-6 text-right font-black text-gold text-sm">
                                        Rp {{ number_format($booking->grand_total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-6 text-center">
                                        <a href="{{ route('invoice', $booking->booking_code) }}" class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-gold hover:text-slate-900 hover:border-gold transition-all mx-auto shadow-sm group-hover:scale-110" title="Cetak Invoice">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-24 text-center">
                                        <div class="w-24 h-24 bg-slate-900/50 rounded-full flex items-center justify-center text-slate-800 text-4xl mx-auto mb-6 border border-white/5">
                                            <i class="fas fa-car-side opacity-20"></i>
                                        </div>
                                        <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">Belum ada riwayat pemesanan.</p>
                                        <a href="{{ route('cars.index') }}" class="text-gold text-[10px] font-black mt-4 inline-block hover:underline uppercase tracking-widest">Mulai Sewa Sekarang</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Floating Action Button for Mobile (PWA Feel) --}}
    <div class="fixed bottom-10 right-6 z-50 md:hidden">
        <a href="{{ route('cars.index') }}" class="w-16 h-16 rounded-full bg-gold text-slate-900 shadow-[0_10px_30px_rgba(212,175,55,0.4)] flex items-center justify-center text-2xl hover:scale-110 active:scale-95 transition-all">
            <i class="fas fa-plus"></i>
        </a>
    </div>
</div>
@endsection
