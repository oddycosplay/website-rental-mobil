<x-guest-layout>
    <x-authentication-card>
        <h2 class="text-2xl font-bold text-center text-white mb-4">Lupa Password?</h2>

        <div class="mb-6 text-sm text-slate-400 text-center leading-relaxed">
            {{ __('Jangan khawatir. Cukup masukkan alamat email Anda dan kami akan mengirimkan tautan reset password agar Anda dapat membuat password baru.') }}
        </div>

        @session('status')
            <div class="mb-6 font-medium text-sm text-green-400 bg-green-400/10 p-4 rounded-xl border border-green-400/20 text-center">
                {{ $value }}
            </div>
        @endsession

        <x-validation-errors class="mb-6 text-red-400 bg-red-400/10 p-4 rounded-xl border border-red-400/20" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-300 mb-2">{{ __('Email Address') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="block w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl focus:ring-gold focus:border-gold placeholder-slate-500 transition-colors shadow-inner" placeholder="nama@email.com" />
            </div>

            <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-[0_0_15px_rgba(212,175,55,0.3)] text-sm font-bold text-slate-900 bg-gradient-to-r from-gold to-gold-light hover:from-gold-light hover:to-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold focus:ring-offset-slate-900 transition-all transform hover:scale-[1.02]">
                KIRIM LINK RESET PASSWORD
            </button>

            <p class="text-center text-sm text-slate-400 mt-6">
                Ingat password Anda? 
                <a href="{{ route('login') }}" class="font-bold text-gold hover:text-gold-light transition-colors ml-1">
                    Kembali ke Login
                </a>
            </p>
        </form>
    </x-authentication-card>
</x-guest-layout>
