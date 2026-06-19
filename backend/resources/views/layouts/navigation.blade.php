<nav x-data="{ open: false, scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 20)"
    class="fixed top-0 left-0 right-0 z-[9999] transition-all duration-500"
    :class="scrolled ? 'bg-slate-900/90 backdrop-blur-xl border-b border-white/10 py-3' : 'bg-transparent py-5'">

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-white p-0.5 shadow-2xl transition-transform group-hover:scale-110">
                            <img src="{{ asset('images/logo-perusahaan.jpeg') }}" alt="Logo" class="w-full h-full object-cover rounded-lg">
                        </div>
                        <div class="flex flex-col -space-y-1">
                            <span class="font-poppins font-black text-lg tracking-tighter text-white group-hover:text-gold transition-colors">
                                SILIWANGI<span class="text-gold">ADMIN</span>
                            </span>
                            <span class="text-[6px] font-black tracking-[0.4em] text-slate-500 uppercase">Pusat Sistem</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:flex">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all {{ request()->routeIs('dashboard') ? 'bg-gold text-slate-950' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        {{ __('Ringkasan Analitik') }}
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative" x-data="{ userOpen: false }">
                    <button @click="userOpen = !userOpen" @click.away="userOpen = false"
                        class="flex items-center gap-3 px-4 py-2 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 hover:border-gold/30 transition-all group">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=D4AF37&color=0F172A&bold=true" class="w-8 h-8 rounded-lg shadow-lg" alt="User">
                        <div class="text-left">
                            <p class="text-[10px] font-bold text-white">{{ Auth::user()->name }}</p>
                            <p class="text-[8px] text-slate-500 uppercase tracking-widest">{{ Auth::user()->roles->first()?->name ?? 'Petugas' }}</p>
                        </div>
                        <i class="fas fa-chevron-down text-[8px] text-slate-600 group-hover:text-gold transition-colors"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="userOpen"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                        class="absolute right-0 mt-3 w-56 bg-slate-900 border border-white/10 rounded-2xl shadow-2xl py-2 z-50 overflow-hidden"
                        x-cloak>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-white hover:bg-white/5 transition-all">
                            <i class="fas fa-user-circle w-4 text-center"></i> {{ __('Pengaturan Profil') }}
                        </a>

                        <div class="h-px bg-white/5 my-1"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-all text-left">
                                <i class="fas fa-power-off w-4 text-center"></i> {{ __('Keluar Sistem') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-white transition-all">
                    <i class="fas" :class="open ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="sm:hidden px-4 pb-6 pt-2">
        <div class="bg-slate-900 border border-white/10 rounded-2xl p-4 shadow-2xl space-y-2">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-4 px-5 py-4 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-gold text-slate-900 font-black' : 'text-slate-400 bg-white/5' }} transition-all text-xs uppercase font-bold tracking-widest">
                <i class="fas fa-chart-line w-5 text-center"></i> {{ __('Dasbor') }}
            </a>

            <div class="h-px bg-white/5 my-2"></div>

            <div class="px-5 py-4 bg-white/5 rounded-xl border border-white/5 flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-gold flex items-center justify-center text-slate-950 font-black">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-white text-xs font-bold">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-500">{{ Auth::user()->email }}</p>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 px-5 py-4 text-slate-400 hover:text-white transition-all text-xs font-bold uppercase tracking-widest">
                <i class="fas fa-user-cog w-5 text-center"></i> {{ __('Profil') }}
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-4 px-5 py-4 text-red-400 hover:text-red-300 transition-all text-xs font-bold uppercase tracking-widest text-left">
                    <i class="fas fa-power-off w-5 text-center"></i> {{ __('Keluar') }}
                </button>
            </form>
        </div>
    </div>
</nav>