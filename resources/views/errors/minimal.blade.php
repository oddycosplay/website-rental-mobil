<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title') - Siliwangi Rental</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <!-- TailwindCSS via CDN for error pages -->
        <script src="https://cdn.tailwindcss.com"></script>
        
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
                margin: 0;
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
                border-radius: 1.5rem;
            }

            .animate-in {
                animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }

            @keyframes slideUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            .text-gold {
                color: var(--gold);
            }
            
            .border-gold {
                border-color: var(--gold);
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="mesh-background"></div>
        <div class="mesh-sphere" style="width: 400px; height: 400px; background: var(--gold); top: -100px; right: -100px;"></div>
        <div class="mesh-sphere" style="width: 300px; height: 300px; background: var(--gold); bottom: -50px; left: -50px; opacity: 0.1;"></div>

        <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-12 relative z-10 min-h-screen">
            
            <div class="glass-card p-10 max-w-2xl w-full text-center animate-in" style="animation-delay: 0.1s;">
                <div class="mb-8">
                    <a href="/" class="inline-flex flex-col items-center gap-4 group">
                        <div class="w-16 h-16 rounded-2xl bg-white p-1 shadow-2xl transition-transform duration-500 group-hover:scale-110 overflow-hidden">
                            <img src="/images/logo-perusahaan.jpeg" alt="Logo" class="w-full h-full object-cover rounded-xl" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                            <div class="w-full h-full items-center justify-center text-slate-900 font-black text-xl rounded-xl hidden">SR</div>
                        </div>
                    </a>
                </div>
                
                <div class="flex flex-col md:flex-row items-center justify-center space-y-6 md:space-y-0 md:space-x-8 mb-8">
                    <div class="text-7xl font-black text-white tracking-tighter border-b-2 md:border-b-0 md:border-r-2 border-white/20 pb-6 md:pb-0 md:pr-8">
                        @yield('code')
                    </div>

                    <div class="text-2xl font-light text-slate-300 uppercase tracking-widest text-center md:text-left">
                        @yield('message')
                    </div>
                </div>
                
                <div class="mt-10">
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-8 py-3 rounded-full bg-white/5 hover:bg-white/10 border border-white/10 text-white font-medium transition-all duration-300 hover:scale-105 group">
                        <i class="fas fa-arrow-left mr-3 group-hover:-translate-x-1 transition-transform"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="absolute bottom-8 text-center animate-in" style="animation-delay: 0.3s;">
                <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em]">
                    &copy; {{ date('Y') }} Siliwangi Rental Indonesia
                </p>
            </div>
        </div>
    </body>
</html>
