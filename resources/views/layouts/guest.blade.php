<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} – Access Portal</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --gold: #D4AF37;
                --gold-light: #F9E29B;
                --gold-dark: #A68A2D;
                --slate-dark: #080E1A;
                --slate-main: #0F172A;
            }

            body {
                font-family: 'Inter', sans-serif;
                background-color: var(--slate-dark);
                color: white;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                position: relative;
                overflow-x: hidden;
            }

            h1, h2, h3, h4, h5, h6 {
                font-family: 'Poppins', sans-serif;
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
            }

            .mesh-sphere {
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                z-index: -1;
                opacity: 0.15;
            }

            .glass-card {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.08);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            }

            .animate-in {
                animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            @keyframes slideUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="antialiased">
        <div class="mesh-background"></div>
        <div class="mesh-sphere" style="width: 400px; height: 400px; background: var(--gold); top: -100px; right: -100px;"></div>
        <div class="mesh-sphere" style="width: 300px; height: 300px; background: var(--gold); bottom: -50px; left: -50px; opacity: 0.1;"></div>

        <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-12 relative z-10">
            <!-- Logo Section -->
            <div class="mb-10 text-center animate-in" style="animation-delay: 0.1s;">
                <a href="/" class="inline-flex flex-col items-center gap-4 group">
                    <div class="w-16 h-16 rounded-2xl bg-white p-1 shadow-2xl transition-transform duration-500 group-hover:scale-110">
                        <img src="{{ asset('images/logo-perusahaan.jpeg') }}" alt="Logo" class="w-full h-full object-cover rounded-xl">
                    </div>
                    <div>
                        <h1 class="text-2xl font-black tracking-tighter text-white uppercase">
                            SILIWANGI<span class="text-gold">RENTAL</span>
                        </h1>
                        <p class="text-[8px] font-black tracking-[0.4em] text-slate-500 uppercase mt-1">Premium Experience Portal</p>
                    </div>
                </a>
            </div>

            <!-- Content Slot -->
            <div class="w-full max-w-md animate-in" style="animation-delay: 0.2s;">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-12 text-center animate-in" style="animation-delay: 0.3s;">
                <p class="text-slate-600 text-[10px] font-black uppercase tracking-[0.2em]">
                    &copy; {{ date('Y') }} Siliwangi Rental Indonesia
                </p>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
