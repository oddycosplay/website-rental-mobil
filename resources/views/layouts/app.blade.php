<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <!-- Theme Check -->
    <script>
        if (localStorage.getItem('theme') === 'light' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: light)').matches)) {
            document.documentElement.classList.add('light');
        } else {
            document.documentElement.classList.remove('light');
        }
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Siliwangi Rental – Premium Car Rental')</title>
    <meta name="description" content="@yield('description', 'Siliwangi Rental – Premium car rental solutions, fast, safe, and reliable in Indonesia.')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Siliwangi Rental – Premium Car Rental')">
    <meta property="og:description" content="@yield('description', 'Siliwangi Rental – Premium car rental solutions, fast, safe, and reliable in Indonesia.')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Siliwangi Rental – Premium Car Rental')">
    <meta property="twitter:description" content="@yield('description', 'Siliwangi Rental – Premium car rental solutions, fast, safe, and reliable in Indonesia.')">
    <meta property="twitter:image" content="@yield('og_image', asset('images/og-image.jpg'))">

    <meta name="theme-color" content="#D4AF37">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Anime.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.2/anime.min.js"></script>

    <!-- Canvas Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>

    <!-- Vite Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @yield('styles')

    <style>
        :root {
            --gold: #D4AF37;
            --gold-light: #F9E29B;
            --gold-dark: #A68A2D;
            --slate-dark: #080E1A;
            --slate-main: #0F172A;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--slate-dark);
            color: white;
            overflow-x: hidden;
            transition: background-color 0.5s ease, color 0.5s ease;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--slate-dark);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gold);
            border-radius: 3px;
            border: 2px solid var(--slate-dark);
        }

        .mesh-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: radial-gradient(at 0% 0%, rgba(212, 175, 55, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(15, 23, 42, 1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(212, 175, 55, 0.05) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(15, 23, 42, 1) 0px, transparent 50%);
            background-color: var(--slate-dark);
            transition: background 0.5s ease, background-color 0.5s ease;
        }

        .mesh-sphere {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.1;
            pointer-events: none;
            transition: opacity 0.5s ease;
        }

        .animate-up {
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .wa-float {
            animation: pulse-wa 2s infinite;
            background: #25D366;
            box-shadow: 0 10px 25px -5px rgba(37, 211, 102, 0.4);
        }

        @keyframes pulse-wa {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 6px 24px rgba(37, 211, 102, 0.5);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 6px 40px rgba(37, 211, 102, 0.8);
            }
        }

        .glass-nav {
            background: rgba(8, 14, 26, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            transition: background 0.5s ease, border-bottom 0.5s ease;
        }

        .nav-center-pill {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: background 0.5s ease, border-color 0.5s ease;
        }

        html.light .nav-center-pill {
            background: rgba(255, 255, 255, 0.8) !important;
            border-color: rgba(15, 23, 42, 0.08) !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05) !important;
        }

        /* PREMIUM LIGHT MODE OVERRIDES */
        html.light body {
            background-color: #F8FAFC;
            color: #0F172A;
        }

        html.light .mesh-background {
            background: radial-gradient(at 0% 0%, rgba(212, 175, 55, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(241, 245, 249, 1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(212, 175, 55, 0.08) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(241, 245, 249, 1) 0px, transparent 50%);
            background-color: #F8FAFC;
        }

        html.light .mesh-sphere {
            opacity: 0.03 !important;
        }

        html.light .bg-slate-900,
        html.light [class*="bg-[#0B1120]"],
        html.light [class*="bg-[#080E1A]"] {
            background-color: #FFFFFF !important;
        }

        html.light [class*="bg-slate-900/90"],
        html.light [class*="bg-slate-900/95"],
        html.light [class*="bg-slate-900/80"],
        html.light [class*="bg-slate-800/40"],
        html.light [class*="bg-slate-800/50"],
        html.light .bg-slate-800 {
            background-color: rgba(255, 255, 255, 0.85) !important;
            border-color: rgba(15, 23, 42, 0.08) !important;
        }

        html.light [class*="bg-slate-900/80"] {
            background-color: rgba(255, 255, 255, 0.9) !important;
            border-color: rgba(15, 23, 42, 0.08) !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.04) !important;
        }

        html.light .bg-gradient-to-br.from-slate-900.via-slate-900.to-slate-800 {
            background-image: linear-gradient(to bottom right, #FFFFFF, #F1F5F9) !important;
            border-color: rgba(15, 23, 42, 0.08) !important;
        }

        html.light .text-white,
        html.light h1,
        html.light h2,
        html.light h3,
        html.light h4,
        html.light h5,
        html.light h6 {
            color: #0F172A !important;
        }

        html.light .text-slate-300 {
            color: #334155 !important;
        }

        html.light .text-slate-400 {
            color: #475569 !important;
        }

        html.light .text-slate-500 {
            color: #64748B !important;
        }

        html.light [class*="border-white/10"],
        html.light [class*="border-white/5"],
        html.light [class*="border-white/20"] {
            border-color: rgba(15, 23, 42, 0.08) !important;
        }

        html.light [class*="bg-white/5"] {
            background-color: rgba(15, 23, 42, 0.04) !important;
        }

        html.light [class*="bg-white/10"] {
            background-color: rgba(15, 23, 42, 0.08) !important;
        }

        html.light [class*="hover:text-white"]:hover {
            color: #0F172A !important;
        }

        html.light [class*="hover:bg-white/10"]:hover {
            background-color: rgba(15, 23, 42, 0.08) !important;
        }

        html.light [class*="hover:bg-white/5"]:hover {
            background-color: rgba(15, 23, 42, 0.04) !important;
        }

        html.light .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }

        html.light nav.bg-slate-900 {
            background-color: rgba(255, 255, 255, 0.9) !important;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08) !important;
        }

        html.light [class*="bg-slate-900/95"] {
            background-color: rgba(255, 255, 255, 0.98) !important;
            border-color: rgba(15, 23, 42, 0.1) !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }

        html.light [class*="aspect-[16/10]"] .text-white,
        html.light [class*="bg-slate-900/60"] .text-white,
        html.light [class*="aspect-[4/3]"] .text-white,
        html.light [class*="px-4"] [class*="py-1.5"].text-white {
            color: #FFFFFF !important;
        }

        html.light a.bg-gold .text-slate-900,
        html.light button.bg-gold .text-slate-900,
        html.light a.bg-gold,
        html.light button.bg-gold {
            color: #0F172A !important;
        }

        html.light input,
        html.light select,
        html.light textarea {
            background-color: #FFFFFF !important;
            color: #0F172A !important;
            border-color: rgba(15, 23, 42, 0.12) !important;
        }

        html.light input::placeholder,
        html.light textarea::placeholder {
            color: #94A3B8 !important;
        }

        html.light .wa-float {
            box-shadow: 0 10px 25px -5px rgba(37, 211, 102, 0.2) !important;
        }
    </style>
</head>

<body class="antialiased overflow-x-hidden relative">
    <div class="mesh-background"></div>
    <div class="mesh-sphere" style="width: 500px; height: 500px; background: var(--gold); top: -200px; right: -100px;"></div>
    <div class="mesh-sphere" style="width: 400px; height: 400px; background: var(--gold); bottom: -100px; left: -100px; opacity: 0.05;"></div>

    <!-- NAVBAR -->
    <nav id="navbar" class="fixed top-0 left-0 right-0 transition-all duration-500 ease-in-out"
        style="z-index: 9999;"
        x-data="{ 
        open: false, 
        scrolled: true, 
        isDesktop: window.innerWidth >= 1024 
        }"
        x-init="window.addEventListener('resize', () => { isDesktop = window.innerWidth >= 1024; if(isDesktop) open = false; })"
        @scroll.window="scrolled = (window.pageYOffset > 20)"
        :class="scrolled ? 'bg-slate-900 shadow-2xl py-3' : 'bg-transparent py-6'"
        :style="{ 
        'z-index': '99999',
        'backdrop-filter': scrolled ? 'blur(20px)' : 'none',
        '-webkit-backdrop-filter': scrolled ? 'blur(20px)' : 'none',
        'border-bottom': scrolled ? '1px solid rgba(255,255,255,0.05)' : 'none'
        }">

        <div class="max-w-8xl mx-auto px-4 sm:px-6 flex items-center relative">
            <!-- Left Section: Logo -->
            <div class="flex-1 flex items-center justify-start">
                <a href="{{ url('/') }}" class="flex items-center gap-2 sm:gap-3 group relative z-[10001] shrink-0">
                    <div class="relative w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-white p-0.5 overflow-hidden shadow-2xl group-hover:scale-105 transition-all duration-500" style="border: 1px solid rgba(255,255,255,0.1);">
                        <img src="{{ asset('images/logo-perusahaan.jpeg') }}" alt="Siliwangi Rental Logo" class="w-full h-full object-cover rounded-[0.6rem]">
                    </div>
                    <div class="flex flex-col -space-y-1">
                        <span class="font-poppins font-black text-lg sm:text-xl tracking-tighter text-white group-hover:text-gold transition-colors duration-500">
                            SILIWANGI<span class="text-gold" x-show="isDesktop">RENTAL</span>
                        </span>
                        <span class="text-[6px] sm:text-[7px] font-black tracking-[0.4em] text-slate-500 uppercase">TRANS NUSA</span>
                    </div>
                </a>
            </div>

            <!-- Center Section: Desktop Navigation -->
            <div class="hidden lg:flex flex-none justify-center">
                <div class="nav-center-pill flex items-center gap-5 px-5 py-3 rounded-2xl border border-white/10 shadow-2xl shadow-black/20">
                    @foreach([
                    '/' => 'Home',
                    '/cars' => 'Fleet',
                    '/about' => 'About',
                    '/faq' => 'FAQ',
                    '/contact' => 'Contact'
                    ] as $path => $label)
                    @php $isActive = request()->is(ltrim($path, '/')) || (request()->is('/') && $path == '/'); @endphp
                    <a href="{{ url($path) }}"
                        class="relative px-10 py-5 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all duration-300 group overflow-hidden {{ $isActive ? 'text-slate-900' : 'text-slate-400 hover:text-white' }}">
                        <span class="relative z-10">{{ $label }}</span>
                        @if($isActive)
                        <div class="absolute inset-0 bg-gradient-to-br from-gold via-gold-light to-gold-dark shadow-lg shadow-gold/20"></div>
                        @else
                        <div class="absolute inset-0 bg-white/5 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Right Section: Actions -->
            <div class="flex-1 flex items-center justify-end gap-2 sm:gap-4 lg:gap-6 relative z-[10001]">
                <!-- Theme Toggle Switcher -->
                <button @click="toggleTheme()" x-data="{ currentTheme: localStorage.getItem('theme') || 'dark' }" @theme-changed.window="currentTheme = $event.detail" class="relative w-10 h-10 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 hover:border-gold/30 transition-all duration-300 text-slate-300 hover:text-white flex items-center justify-center group overflow-hidden shrink-0" title="Toggle Theme">
                    <i class="fas fa-sun text-gold text-lg transition-all duration-500 absolute" :class="currentTheme === 'light' ? 'rotate-0 scale-100 opacity-100' : 'rotate-90 scale-0 opacity-0'"></i>
                    <i class="fas fa-moon text-blue-400 text-lg transition-all duration-500 absolute" :class="currentTheme === 'dark' ? 'rotate-0 scale-100 opacity-100' : '-rotate-90 scale-0 opacity-0'"></i>
                </button>

                <!-- Cart Counter -->
                <livewire:cart-counter />

                @auth

                <!-- Notifications Dropdown -->
                <div class="relative" x-data="{ notifOpen: false }">
                    <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false"
                        class="relative p-2.5 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 hover:border-gold/30 transition-all duration-300 text-slate-300 hover:text-white">
                        <i class="fas fa-bell"></i>
                        @if(isset($unpaidBookings) && $unpaidBookings->count() > 0)
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 rounded-full bg-red-500 border-2 border-slate-900 animate-pulse"></span>
                        @endif
                    </button>

                    <!-- Notif Menu -->
                    <div x-show="notifOpen"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                        class="absolute right-0 mt-3 w-80 bg-slate-900/95 border border-white/10 rounded-2xl shadow-2xl py-2 z-50 overflow-hidden"
                        style="backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px);"
                        x-cloak>

                        <div class="px-4 py-2 border-b border-white/5 flex justify-between items-center">
                            <span class="text-[10px] font-black text-gold uppercase tracking-widest">Payment Notifications</span>
                            <span class="text-[10px] font-bold text-white bg-white/10 px-2 py-0.5 rounded-lg">{{ $unpaidBookings->count() }}</span>
                        </div>

                        <div class="max-h-[300px] overflow-y-auto">
                            @forelse($unpaidBookings as $ub)
                            <a href="{{ route('invoice', $ub->booking_code) }}" class="block px-4 py-3 hover:bg-white/5 transition-colors border-b border-white/5 last:border-0">
                                <div class="flex justify-between items-start mb-1">
                                    <span class="text-[10px] font-black text-white uppercase">{{ $ub->booking_code }}</span>
                                    <span class="text-[8px] font-bold text-red-400 bg-red-400/10 px-1.5 py-0.5 rounded uppercase">Unpaid</span>
                                </div>
                                <p class="text-xs text-slate-400 font-medium mb-1">{{ $ub->car->car_name }}</p>
                                <p class="text-[10px] font-bold text-gold">Rp {{ number_format($ub->grand_total, 0, ',', '.') }}</p>
                            </a>
                            @empty
                            <div class="px-4 py-6 text-center text-slate-500">
                                <i class="fas fa-check-circle text-2xl mb-2 text-emerald-500/50"></i>
                                <p class="text-[10px] font-bold uppercase tracking-widest">All invoices settled</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div class="relative" x-data="{ dropdownOpen: false }">
                    <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false"
                        class="flex items-center gap-2 sm:gap-3 p-1 sm:pr-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 hover:border-gold/30 transition-all duration-300 group">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-gold to-gold-dark flex items-center justify-center text-slate-900 shadow-lg group-hover:scale-105 transition-transform">
                            <i class="fas fa-user-circle text-lg"></i>
                        </div>
                        <div class="text-left hidden md:block">
                            <p class="text-xs font-bold text-white truncate max-w-[100px]">{{ Auth::user()->name }}</p>
                        </div>
                        <i class="fas fa-chevron-down text-[8px] text-slate-500 group-hover:text-gold transition-colors" x-show="isDesktop"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="dropdownOpen"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                        class="absolute right-0 mt-3 w-64 bg-slate-900/95 border border-white/10 rounded-2xl shadow-2xl py-3 z-50 overflow-hidden"
                        style="backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px);"
                        x-cloak>

                        <div class="px-5 py-3 mb-2 border-b border-white/5">
                            <p class="text-[9px] font-black text-gold uppercase tracking-[0.2em] mb-1">Signed in as</p>
                            <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        </div>

                        <div class="px-2 space-y-1">
                            @if(Auth::user()->hasAnyRole(['super-admin', 'admin', 'owner', 'finance', 'staff-operasional', 'it-support', 'driver']))
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-gold hover:bg-gold/5 transition-all">
                                <i class="fas fa-shield-halved w-5 text-center"></i> Admin Panel
                            </a>
                            @endif

                            <div class="h-px bg-white/5 my-2 mx-4"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-all text-left">
                                    <i class="fas fa-power-off w-5 text-center"></i> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="flex items-center gap-3 sm:gap-4 lg:gap-8">
                    <a href="{{ route('login') }}" class="text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 hover:text-gold transition-all duration-300">Sign In</a>
                    <a href="{{ url('/cars') }}" class="relative px-4 sm:px-6 py-2.5 sm:py-3 rounded-xl bg-gradient-to-br from-gold to-gold-dark text-slate-950 font-black text-[9px] sm:text-[10px] uppercase tracking-widest hover:scale-105 hover:shadow-xl hover:shadow-gold/20 active:scale-95 transition-all duration-300">
                        <span x-show="isDesktop || window.innerWidth > 500">Book Now</span>
                        <i class="fas fa-calendar-alt" x-show="!isDesktop && window.innerWidth <= 500"></i>
                    </a>
                </div>
                @endauth

                <!-- Mobile Menu Toggle -->
                <button @click="open = !open" class="lg:hidden group p-1 focus:outline-none shrink-0" x-show="!isDesktop">
                    <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center transition-all group-hover:bg-gold/10 group-hover:border-gold/30">
                        <div class="flex flex-col gap-1.5 items-end">
                            <span class="block h-0.5 bg-white transition-all duration-300" :class="{'w-5 rotate-45 translate-y-2': open, 'w-5': !open}"></span>
                            <span class="block h-0.5 bg-gold transition-all duration-300" :class="{'opacity-0 w-0': open, 'w-3': !open}"></span>
                            <span class="block h-0.5 bg-white transition-all duration-300" :class="{'w-5 -rotate-45 -translate-y-2': open, 'w-4': !open}"></span>
                        </div>
                    </div>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Overlay -->
        <div x-show="open && !isDesktop"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="absolute inset-x-0 top-full mt-4 mx-4 sm:mx-6 bg-slate-900/95 border border-white/10 rounded-3xl shadow-2xl overflow-hidden p-4 sm:p-6 flex flex-col gap-2"
            style="backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px); z-index: 9999;"
            x-cloak>

            <div class="flex justify-between items-center mb-4 px-2">
                <span class="text-[10px] font-black text-gold uppercase tracking-[0.3em]">Navigation Menu</span>
                <button @click="open = false" class="text-white/40 hover:text-white transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Mobile Theme Switcher -->
            <div class="flex justify-between items-center px-5 py-4 bg-white/5 rounded-2xl border border-white/5 mb-2" x-data="{ currentTheme: localStorage.getItem('theme') || 'dark' }" @theme-changed.window="currentTheme = $event.detail">
                <span class="text-xs uppercase font-bold tracking-widest text-slate-400">Theme Mode</span>
                <button @click="toggleTheme()" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-slate-300 hover:text-white transition-all">
                    <i class="fas fa-sun text-gold text-sm" x-show="currentTheme === 'light'"></i>
                    <i class="fas fa-moon text-blue-400 text-sm" x-show="currentTheme === 'dark'"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest" x-text="currentTheme === 'light' ? 'Light' : 'Dark'"></span>
                </button>
            </div>

            @foreach([
            '/' => ['icon' => 'fa-home', 'label' => 'Home'],
            '/cars' => ['icon' => 'fa-car-side', 'label' => 'Fleet'],
            '/about' => ['icon' => 'fa-users', 'label' => 'About Us'],
            '/faq' => ['icon' => 'fa-circle-question', 'label' => 'Help & FAQ'],
            '/contact' => ['icon' => 'fa-headset', 'label' => 'Contact Us']
            ] as $path => $info)
            <a href="{{ url($path) }}"
                class="flex items-center gap-4 px-5 py-4 rounded-2xl {{ request()->is(ltrim($path, '/')) ? 'bg-gold text-slate-900 font-black' : 'text-slate-400 bg-white/5 border border-white/5' }} transition-all">
                <i class="fas {{ $info['icon'] }} w-6 text-center text-base"></i>
                <span class="text-xs uppercase font-bold tracking-widest">{{ $info['label'] }}</span>
            </a>
            @endforeach

            <div class="h-px bg-white/5 my-4"></div>

            @auth
            <div class="flex items-center gap-4 px-5 py-4 bg-white/5 rounded-2xl border border-white/5 mb-2">
                <div class="w-10 h-10 rounded-xl bg-gold flex items-center justify-center text-slate-950 font-black">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-white text-xs font-bold truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-500 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-3 py-4 rounded-2xl bg-red-500/10 text-red-400 font-bold text-xs uppercase tracking-widest border border-red-500/10">
                    <i class="fas fa-power-off"></i> Logout
                </button>
            </form>
            @else
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('login') }}" class="flex items-center justify-center py-4 rounded-2xl bg-white/5 text-white font-bold text-xs uppercase tracking-widest border border-white/5">Sign In</a>
                <a href="{{ route('register') }}" class="flex items-center justify-center py-4 rounded-2xl bg-gold text-slate-950 font-black text-xs uppercase tracking-widest">Sign Up</a>
            </div>
            @endauth
        </div>
    </nav>

    <!-- PAGE CONTENT -->
    <main class="min-h-screen animate-up">
        @yield('content')
        @isset($slot)
        {{ $slot }}
        @endisset
    </main>

    <!-- FOOTER -->
    <footer class="bg-[#080E1A] border-t border-white/5 pt-20 pb-10 relative overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-gold/30 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-20">
                <!-- Brand Section -->
                <div class="space-y-8" data-aos="fade-up">
                    <a href="{{ url('/') }}" class="flex items-center gap-4 group">
                        <div class="relative w-12 h-12 rounded-2xl bg-white p-0.5 overflow-hidden shadow-2xl group-hover:scale-105 transition-all duration-500 ring-1 ring-white/20">
                            <img src="{{ asset('images/logo-perusahaan.jpeg') }}" alt="Siliwangi Rental Logo" class="w-full h-full object-cover rounded-[0.8rem]">
                        </div>
                        <div class="flex flex-col -space-y-1">
                            <span class="font-poppins font-black text-xl tracking-tighter text-white">SILIWANGI<span class="text-gold">RENTAL</span></span>
                            <span class="text-[7px] font-black tracking-[0.4em] text-slate-500 uppercase">Premium Experience</span>
                        </div>
                    </a>
                    <p class="text-slate-400 text-sm leading-relaxed font-medium">
                        Indonesia's leading premium car rental service. We deliver first-class comfort with the latest fleet and professional driver services.
                    </p>
                    <div class="flex gap-4">
                        @foreach(['instagram', 'facebook-f', 'whatsapp', 'tiktok'] as $social)
                        <a href="#" class="w-11 h-11 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-gold hover:text-slate-900 hover:border-gold hover:-translate-y-1.5 transition-all duration-300 group">
                            <i class="fab fa-{{ $social }} text-lg"></i>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Services -->
                <div class="space-y-8" data-aos="fade-up" data-aos-delay="100">
                    <h4 class="text-white font-black text-xs tracking-[0.2em] uppercase">Our Services</h4>
                    <div class="flex flex-col gap-4">
                        @foreach(['Self-Drive Rental', 'Rental with Driver', 'Monthly Rental', 'Corporate Rental', 'Airport Transfer', 'Wedding Car'] as $service)
                        <a href="#" class="text-slate-500 text-xs font-bold hover:text-gold hover:translate-x-2 transition-all duration-300 flex items-center gap-3 group">
                            <span class="w-1.5 h-1.5 rounded-full bg-gold/30 group-hover:bg-gold transition-colors"></span>
                            {{ $service }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Navigation -->
                <div class="space-y-8" data-aos="fade-up" data-aos-delay="200">
                    <h4 class="text-white font-black text-xs tracking-[0.2em] uppercase">Quick Links</h4>
                    <div class="flex flex-col gap-4">
                        @foreach([
                        '/' => 'Home',
                        '/cars' => 'Car Fleet',
                        '/about' => 'About Us',
                        '/faq' => 'Help Center / FAQ',
                        '/contact' => 'Contact Us'
                        ] as $url => $label)
                        <a href="{{ url($url) }}" class="text-slate-500 text-xs font-bold hover:text-white transition-all duration-300">{{ $label }}</a>
                        @endforeach
                    </div>
                </div>

                <!-- Contact -->
                <div class="space-y-8" data-aos="fade-up" data-aos-delay="300">
                    <h4 class="text-white font-black text-xs tracking-[0.2em] uppercase">Contact Center</h4>
                    <div class="space-y-6">
                        <a href="tel:+6281234567890" class="flex items-center gap-5 group">
                            <div class="w-12 h-12 rounded-2xl bg-gold/5 border border-gold/10 flex items-center justify-center text-gold group-hover:bg-gold group-hover:text-slate-900 transition-all duration-300">
                                <i class="fas fa-phone-volume text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Phone & WhatsApp</p>
                                <p class="text-sm font-bold text-white">+62 812-3456-7890</p>
                            </div>
                        </a>
                        <a href="mailto:info@siliwangirental.com" class="flex items-center gap-5 group">
                            <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 group-hover:bg-white group-hover:text-slate-900 transition-all duration-300">
                                <i class="fas fa-envelope-open-text text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Email Support</p>
                                <p class="text-sm font-bold text-white">info@siliwangirental.com</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="pt-10 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex flex-col items-center md:items-start gap-2">
                    <p class="text-slate-600 text-[10px] font-black uppercase tracking-[0.25em]">
                        © {{ date('Y') }} Siliwangi Rental Indonesia. All Rights Reserved.
                    </p>
                    <div class="flex gap-4 text-[9px] font-bold text-slate-700 uppercase tracking-widest">
                        <a href="#" class="hover:text-gold transition-colors">Privacy Policy</a>
                        <a href="#" class="hover:text-gold transition-colors">Terms of Service</a>
                    </div>
                </div>

                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-4 filter grayscale opacity-30 hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                        <i class="fab fa-cc-visa text-3xl text-white"></i>
                        <i class="fab fa-cc-mastercard text-3xl text-white"></i>
                        <i class="fab fa-cc-apple-pay text-3xl text-white"></i>
                    </div>
                    <div class="h-10 w-px bg-white/5"></div>
                    <div class="text-right hidden sm:block">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Secure Payment by</p>
                        <p class="text-[11px] font-black text-white tracking-widest italic uppercase">Midtrans</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/628973716530?text=Hello%20Siliwangi%20Rental%2C%20I%20am%20interested%20in%20car%20rental%20information" class="wa-float fixed bottom-6 right-6 z-50 w-14 h-14 bg-[#25D366] rounded-full flex items-center justify-center text-white text-3xl shadow-lg hover:scale-110 transition-transform" target="_blank" title="Chat WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    @livewireScripts
    <script>
        function toggleTheme() {
            const isLight = document.documentElement.classList.toggle('light');
            const newTheme = isLight ? 'light' : 'dark';
            localStorage.setItem('theme', newTheme);
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: newTheme }));
            
            // Dispatch dynamic toast notification
            window.dispatchEvent(new CustomEvent('show-toast', { 
                detail: { 
                    type: 'success', 
                    message: `Tampilan dialihkan ke ${newTheme.toUpperCase()} mode!` 
                } 
            }));
        }
    </script>
    @yield('scripts')
    @stack('scripts')
    <!-- AOS Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register("{{ asset('sw.js') }}").then(registration => {
                    console.log('SW registered: ', registration);
                }).catch(registrationError => {
                    console.log('SW registration failed: ', registrationError);
                });
            });
        }

        // Custom Install Prompt
        let deferredPrompt;
        const installBtn = document.createElement('div');
        installBtn.id = 'pwa-install-prompt';
        installBtn.className = 'fixed bottom-24 right-6 z-[60] bg-slate-800/90 backdrop-blur-xl border border-gold/30 p-4 rounded-2xl shadow-2xl flex flex-col items-center gap-3 transition-all transform translate-y-32 opacity-0 max-w-[200px] text-center';
        installBtn.innerHTML = `
            <div class="w-12 h-12 rounded-xl bg-gold/10 flex items-center justify-center text-gold text-2xl mb-1">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <p class="text-white text-xs font-bold leading-tight">Install Siliwangi Rental App?</p>
            <button id="pwa-install-btn" class="w-full py-2 bg-gold text-slate-900 rounded-lg text-[10px] font-black uppercase tracking-widest hover:scale-105 transition-transform">Install Now</button>
            <button id="pwa-close-btn" class="absolute -top-2 -right-2 w-6 h-6 bg-slate-700 text-white rounded-full text-xs flex items-center justify-center border border-white/10"><i class="fas fa-times"></i></button>
        `;
        document.body.appendChild(installBtn);

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            installBtn.classList.remove('translate-y-32', 'opacity-0');
        });

        document.getElementById('pwa-install-btn').addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const {
                    outcome
                } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    installBtn.classList.add('translate-y-32', 'opacity-0');
                }
                deferredPrompt = null;
            }
        });

        document.getElementById('pwa-close-btn').addEventListener('click', () => {
            installBtn.classList.add('translate-y-32', 'opacity-0');
        });
    </script>
    <!-- Toast Notification System -->
    <div x-data="{ 
            toasts: [], 
            add(e) {
                const id = Date.now();
                this.toasts.push({
                    id,
                    type: e.detail.type || 'info',
                    message: e.detail.message,
                    icon: e.detail.type === 'success' ? 'fa-check-circle' : (e.detail.type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle')
                });
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 5000);
            }
        }"
        @show-toast.window="add($event)"
        class="fixed bottom-10 left-10 z-[99999] flex flex-col gap-4 pointer-events-none">

        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="true"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-x-12 scale-90"
                x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                x-transition:leave-end="opacity-0 -translate-x-12 scale-90"
                class="pointer-events-auto flex items-center gap-4 px-6 py-4 rounded-2xl bg-slate-900/90 backdrop-blur-xl border border-white/10 shadow-2xl min-w-[300px]"
                :class="{ 'border-emerald-500/30': toast.type === 'success', 'border-red-500/30': toast.type === 'error', 'border-gold/30': toast.type === 'info' }">

                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg"
                    :class="{ 'bg-emerald-500/20 text-emerald-500': toast.type === 'success', 'bg-red-500/20 text-red-400': toast.type === 'error', 'bg-gold/20 text-gold': toast.type === 'info' }">
                    <i class="fas" :class="toast.icon"></i>
                </div>

                <div class="flex-1">
                    <p class="text-xs font-black uppercase tracking-widest mb-1"
                        :class="{ 'text-emerald-500': toast.type === 'success', 'text-red-400': toast.type === 'error', 'text-gold': toast.type === 'info' }"
                        x-text="toast.type"></p>
                    <p class="text-white text-xs font-bold leading-relaxed" x-text="toast.message"></p>
                </div>

                <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="text-white/20 hover:text-white transition-colors">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </template>
    </div>

</body>

</html>