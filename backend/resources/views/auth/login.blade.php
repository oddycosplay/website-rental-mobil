<x-guest-layout>
    <x-authentication-card>
        <h2 class="text-2xl font-bold text-center text-white mb-8">Login ke Akun Anda</h2>

        <x-validation-errors class="mb-6 text-red-400 bg-red-400/10 p-4 rounded-xl border border-red-400/20" />

        @session('status')
            <div class="mb-6 font-medium text-sm text-green-400 bg-green-400/10 p-4 rounded-xl border border-green-400/20">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="block w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl focus:ring-gold focus:border-gold placeholder-slate-500 transition-colors shadow-inner" placeholder="nama@email.com" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-300 mb-2">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl focus:ring-gold focus:border-gold placeholder-slate-500 transition-colors shadow-inner" placeholder="••••••••" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center cursor-pointer group">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-700 bg-slate-900/50 text-gold shadow-sm focus:ring-gold focus:ring-offset-slate-900 transition-colors" />
                    <span class="ms-2 text-sm text-slate-400 group-hover:text-slate-300 transition-colors">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-gold hover:text-gold-light transition-colors" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-[0_0_15px_rgba(212,175,55,0.3)] text-sm font-bold text-slate-900 bg-gradient-to-r from-gold to-gold-light hover:from-gold-light hover:to-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold focus:ring-offset-slate-900 transition-all transform hover:scale-[1.02]">
                LOG IN SECURELY
            </button>

            @if (Route::has('register'))
                <p class="text-center text-sm text-slate-400 mt-6">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-bold text-gold hover:text-gold-light transition-colors ml-1">
                        Daftar Sekarang
                    </a>
                </p>
            @endif
        </form>
    </x-authentication-card>
</x-guest-layout>
