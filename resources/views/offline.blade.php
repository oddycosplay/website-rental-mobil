@extends('layouts.app')

@section('title', 'Koneksi Terputus – Siliwangi Rental')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-900 px-6">
    <div class="text-center" data-aos="zoom-in">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gold/10 text-gold text-5xl mb-8">
            <i class="fas fa-wifi-slash"></i>
        </div>
        <h1 class="font-poppins text-3xl md:text-4xl font-extrabold text-white mb-4">Sepertinya Anda Offline</h1>
        <p class="text-slate-400 text-lg max-w-md mx-auto mb-10">
            Koneksi internet Anda terputus. Silakan cek kembali koneksi Anda untuk melanjutkan pemesanan armada premium kami.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="window.location.reload()" class="px-8 py-4 rounded-2xl bg-gold text-slate-900 font-bold hover:scale-105 transition-transform shadow-xl shadow-gold/20">
                <i class="fas fa-sync-alt mr-2"></i> Coba Lagi
            </button>
            <a href="/" class="px-8 py-4 rounded-2xl border border-white/10 text-white font-bold hover:bg-white/5 transition-all">
                <i class="fas fa-home mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
