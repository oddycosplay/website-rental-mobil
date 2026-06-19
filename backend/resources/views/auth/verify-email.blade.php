<x-guest-layout>
    <x-authentication-card>
        <h2 class="text-2xl font-bold text-center text-white mb-4">Verifikasi Email Anda</h2>

        <div class="mb-6 text-sm text-slate-400 text-center leading-relaxed">
            Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan ke email Anda. Jika Anda tidak menerima email tersebut, kami akan dengan senang hati mengirimkan tautan baru.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 font-medium text-sm text-green-400 bg-green-400/10 p-4 rounded-xl border border-green-400/20 text-center">
                Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda berikan saat mendaftar.
            </div>
        @endif

        <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full sm:w-auto flex justify-center items-center py-3 px-6 border border-transparent rounded-xl shadow-[0_0_15px_rgba(212,175,55,0.3)] text-sm font-bold text-slate-900 bg-gradient-to-r from-gold to-gold-light hover:from-gold-light hover:to-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold focus:ring-offset-slate-900 transition-all transform hover:scale-[1.02]">
                    KIRIM ULANG EMAIL
                </button>
            </form>

            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('profile.show') }}" class="font-bold text-gold hover:text-gold-light transition-colors">
                    Edit Profil
                </a>

                <span class="text-slate-600">|</span>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="font-bold text-slate-400 hover:text-slate-300 transition-colors">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
