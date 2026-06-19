<x-guest-layout>
    <x-authentication-card>
        <h2 class="text-2xl font-bold text-center text-white mb-8">Daftar Akun Baru</h2>

        <x-validation-errors class="mb-6 text-red-400 bg-red-400/10 p-4 rounded-xl border border-red-400/20" />

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="block w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl focus:ring-gold focus:border-gold placeholder-slate-500 transition-colors shadow-inner" placeholder="Nama Anda" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="block w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl focus:ring-gold focus:border-gold placeholder-slate-500 transition-colors shadow-inner" placeholder="nama@email.com" />
            </div>



            <div>
                <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl focus:ring-gold focus:border-gold placeholder-slate-500 transition-colors shadow-inner" placeholder="Minimal 8 karakter" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="block w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl focus:ring-gold focus:border-gold placeholder-slate-500 transition-colors shadow-inner" placeholder="Ulangi password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div>
                    <label for="terms" class="flex items-start group cursor-pointer">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required
                                class="rounded border-slate-700 bg-slate-900/50 text-gold shadow-sm focus:ring-gold focus:ring-offset-slate-900 transition-colors" />
                        </div>
                        <div class="ml-3 text-sm">
                            <span class="text-slate-400 group-hover:text-slate-300 transition-colors">
                                Saya setuju dengan <a target="_blank" href="{{ route('terms.show') }}" class="font-bold text-gold hover:text-gold-light transition-colors">Syarat Layanan</a> dan <a target="_blank" href="{{ route('policy.show') }}" class="font-bold text-gold hover:text-gold-light transition-colors">Kebijakan Privasi</a>
                            </span>
                        </div>
                    </label>
                </div>
            @endif

            <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-[0_0_15px_rgba(212,175,55,0.3)] text-sm font-bold text-slate-900 bg-gradient-to-r from-gold to-gold-light hover:from-gold-light hover:to-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold focus:ring-offset-slate-900 transition-all transform hover:scale-[1.02]">
                DAFTAR SEKARANG
            </button>

            <p class="text-center text-sm text-slate-400 mt-6">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-gold hover:text-gold-light transition-colors ml-1">
                    Log in di sini
                </a>
            </p>
        </form>
    </x-authentication-card>
</x-guest-layout>
