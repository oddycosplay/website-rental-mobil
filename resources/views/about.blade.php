@extends('layouts.app')
@section('title', 'About Us – Siliwangi Rental')
@section('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.02);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .glass-card-hover:hover {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(212, 175, 55, 0.3);
        transform: translateY(-5px);
    }

    .text-gradient-gold {
        background: linear-gradient(135deg, #FFD700 0%, #FDB931 50%, #D4AF37 100%);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .bg-grid-pattern {
        background-image: radial-gradient(rgba(212, 175, 55, 0.05) 1px, transparent 1px);
        background-size: 30px 30px;
    }

    .shimmer {
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
        background-size: 200% 100%;
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }

        100% {
            background-position: 200% 0;
        }
    }

    .blob-animate {
        animation: blob-float 20s infinite alternate;
    }

    @keyframes blob-float {
        0% {
            transform: translate(0, 0) scale(1);
        }

        33% {
            transform: translate(30px, -50px) scale(1.1);
        }

        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }

        100% {
            transform: translate(0, 0) scale(1);
        }
    }
</style>
@endsection

@section('content')
<section class="relative pt-40 pb-20 overflow-hidden bg-[#080B14]">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
    <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-gold/5 rounded-full blur-[150px] -translate-y-1/2 translate-x-1/4 blob-animate"></div>
    <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-blue-600/5 rounded-full blur-[120px] translate-y-1/4 -translate-x-1/4 blob-animate" style="animation-delay: -5s;"></div>

    <div class="container mx-auto px-6 relative z-10">
        <!-- Hero Section -->
        <div class="text-center max-w-4xl mx-auto mb-20" data-aos="fade-up">
            <div class="inline-flex items-center gap-2.5 px-5 py-2 rounded-full bg-white/5 border border-white/10 mb-8 backdrop-blur-3xl shadow-xl relative group overflow-hidden">
                <div class="absolute inset-0 shimmer opacity-50"></div>
                <span class="w-2 h-2 rounded-full bg-gold shadow-[0_0_10px_#FFD700] animate-pulse relative z-10"></span>
                <span class="text-white/90 text-[9px] font-black uppercase tracking-[0.4em] relative z-10">The Standard of Excellence</span>
            </div>
            <h1 class="font-poppins font-black text-5xl md:text-7xl text-white mb-8 leading-[1] tracking-tighter">
                Explore Without <br>
                <span class="text-gradient-gold drop-shadow-[0_0_40px_rgba(212,175,55,0.3)]">Comfort Limits</span>
            </h1>
            <p class="text-slate-400 text-lg md:text-xl leading-relaxed font-medium max-w-2xl mx-auto mb-12 opacity-90">
                Bringing a new standard in luxury vehicle rental that prioritizes precision, privacy, and absolute satisfaction.
            </p>

            <div class="flex justify-center">
                <div class="group cursor-pointer">
                    <div class="w-8 h-12 rounded-full border-2 border-white/10 flex justify-center pt-2.5 backdrop-blur-md group-hover:border-gold/30 transition-colors">
                        <div class="w-1 h-3 bg-gold rounded-full animate-bounce shadow-[0_0_8px_#FFD700]"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Experience Section -->
        <div class="flex flex-col lg:flex-row gap-16 lg:gap-24 items-center mb-40">
            <div class="w-full lg:w-5/12 relative" data-aos="fade-right">
                <div class="aspect-[4/5] w-full rounded-[3.5rem] overflow-hidden border border-white/5 relative group shadow-xl">
                    <!-- Faux Image Placeholder with Premium Pattern -->
                    <div class="absolute inset-0 bg-[#0B0F1A] flex items-center justify-center overflow-hidden">
                        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
                        <i class="fas fa-gem text-white/[0.02] text-[20rem] rotate-12 group-hover:scale-110 group-hover:rotate-0 transition-transform duration-[2000ms]"></i>
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent"></div>
                    </div>

                    <div class="absolute inset-x-6 bottom-6">
                        <div class="glass-card p-8 border-gold/20 bg-slate-950/80 backdrop-blur-3xl rounded-[2.5rem] shadow-xl">
                            <div class="flex items-center gap-4 mb-5">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gold/30 to-gold/10 text-gold flex items-center justify-center border border-gold/30 shadow-xl">
                                    <i class="fas fa-map-marker-alt text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-white font-black text-lg tracking-tight uppercase">Operational Hub</h3>
                                    <p class="text-gold text-[9px] font-black uppercase tracking-[0.3em]">Bandung, West Java</p>
                                </div>
                            </div>
                            <p class="text-slate-400 text-xs leading-relaxed font-medium">Integrated logistics and fleet services to guarantee timely unit availability.</p>
                        </div>
                    </div>
                </div>

                <!-- Decorative element -->
                <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-gold/10 rounded-full blur-[60px] -z-10"></div>
            </div>

            <div class="w-full lg:w-7/12" data-aos="fade-left">
                <div class="inline-flex items-center gap-3 px-5 py-2 rounded-xl bg-gold/5 border border-gold/10 mb-8 backdrop-blur-xl">
                    <span class="w-2 h-2 rounded-full bg-gold shadow-[0_0_10px_#FFD700]"></span>
                    <span class="text-gold text-[10px] font-black uppercase tracking-[0.2em]">Our Legacy — Est. 2016</span>
                </div>

                <h2 class="font-poppins font-black text-4xl md:text-6xl text-white mb-8 leading-[1.1] tracking-tighter">
                    Dedication for <span class="text-gold">8 Years</span> <br> Without Compromise.
                </h2>
                <p class="text-slate-400 text-lg leading-relaxed mb-12 font-medium max-w-xl opacity-80">
                    Siliwangi Rental is not just a fleet provider; we are journey curators ensuring every second behind the wheel becomes a precious memory.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach([
                    ['num' => '500+', 'label' => 'Selected Fleet', 'icon' => 'fa-car-side'],
                    ['num' => '15K+', 'label' => 'Loyal Clients', 'icon' => 'fa-user-check'],
                    ['num' => '24/7', 'label' => 'Standby Support', 'icon' => 'fa-headset'],
                    ['num' => '4.9', 'label' => 'Satisfaction Rating', 'icon' => 'fa-star'],
                    ] as $stat)
                    <div class="glass-card p-6 rounded-2xl border-white/5 hover:border-gold/30 hover:bg-white/[0.04] transition-all duration-500 group relative overflow-hidden">
                        <div class="absolute -right-6 -bottom-6 text-white/[0.03] text-6xl group-hover:scale-110 group-hover:-rotate-12 transition-transform duration-1000">
                            <i class="fas {{ $stat['icon'] }}"></i>
                        </div>
                        <div class="relative z-10">
                            <div class="text-3xl font-black text-white mb-2 tracking-tighter group-hover:text-gold transition-colors">{{ $stat['num'] }}</div>
                            <div class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em]">{{ $stat['label'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-40 bg-[#060912] overflow-hidden relative border-y border-white/5">
    <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center max-w-2xl mx-auto mb-20" data-aos="fade-up">
            <span class="inline-block px-5 py-2 rounded-full bg-gold/10 text-gold text-[9px] font-black uppercase tracking-[0.4em] mb-6 border border-gold/20 backdrop-blur-xl">The Core Pillars</span>
            <h2 class="font-poppins font-black text-4xl text-white tracking-tighter mb-4">Strength of Our <span class="text-gold">Character</span></h2>
            <p class="text-slate-500 text-base font-medium max-w-xl mx-auto">Fundamental values that form the identity of Siliwangi Rental in serving every customer.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            @foreach([
            ['title' => 'Integrity', 'desc' => 'Every kilometer you travel is built on cost transparency and service honesty.', 'icon' => 'fa-fingerprint', 'glow' => 'gold'],
            ['title' => 'Excellence', 'desc' => 'Strict unit quality standards, ensuring every journey feels like a new car.', 'icon' => 'fa-crown', 'glow' => 'gold'],
            ['title' => 'Innovation', 'desc' => 'Adopting the latest technology to provide reservation ease like never before.', 'icon' => 'fa-bolt', 'glow' => 'blue-500'],
            ] as $value)
            <div class="text-center group" data-aos="fade-up">
                <div class="w-20 h-20 rounded-[1.5rem] bg-slate-900 border border-white/10 flex items-center justify-center text-gold text-2xl mx-auto mb-8 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-700 shadow-xl relative">
                    <div class="absolute inset-0 rounded-[1.5rem] bg-gold/5 blur-xl group-hover:bg-gold/20 transition-all"></div>
                    <i class="fas {{ $value['icon'] }} relative z-10"></i>
                </div>
                <h4 class="text-white font-black text-xl mb-4 tracking-tight group-hover:text-gold transition-colors">{{ $value['title'] }}</h4>
                <p class="text-slate-500 leading-relaxed text-sm font-medium px-4">{{ $value['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="py-40 bg-[#080B14]">
    <div class="container mx-auto px-6">
        <div class="flex flex-col lg:flex-row gap-12 items-stretch">
            <!-- Vision -->
            <div class="w-full lg:w-1/2 glass-card glass-card-hover p-12 rounded-[2.5rem] relative overflow-hidden group shadow-xl" data-aos="fade-up">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-gold/5 rounded-full blur-[100px] transition-all group-hover:bg-gold/10"></div>
                <div class="w-16 h-16 rounded-2xl bg-gold/10 text-gold flex items-center justify-center text-2xl mb-10 border border-gold/20 shadow-inner">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 class="text-white font-black text-3xl mb-6 tracking-tighter uppercase">Future <span class="text-gold">Vision</span></h3>
                <p class="text-slate-400 leading-relaxed text-lg font-medium opacity-80">
                    Revolutionizing the vehicle rental industry by becoming the most trusted integrated mobility ecosystem in Southeast Asia through uncompromising quality.
                </p>
            </div>

            <!-- Mission -->
            <div class="w-full lg:w-1/2 glass-card glass-card-hover p-12 rounded-[2.5rem] relative overflow-hidden group shadow-xl" data-aos="fade-up" data-aos-delay="200">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-600/5 rounded-full blur-[100px] transition-all group-hover:bg-blue-600/10"></div>
                <div class="w-16 h-16 rounded-2xl bg-blue-600/10 text-blue-400 flex items-center justify-center text-2xl mb-10 border border-blue-600/20 shadow-inner">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <h3 class="text-white font-black text-3xl mb-6 tracking-tighter uppercase">Strategic <span class="text-blue-400">Mission</span></h3>
                <ul class="space-y-5">
                    @foreach([
                    'Curating vehicle fleets with the highest specifications',
                    'Data-driven and empathy-based service infrastructure',
                    'Layered security systems for every transaction',
                    'Continuous collaboration with global technology partners'
                    ] as $misi)
                    <li class="flex items-start gap-4 text-slate-400 text-base font-medium group/li">
                        <div class="w-6 h-6 rounded-full bg-gold/10 flex items-center justify-center shrink-0 mt-0.5 group-hover/li:bg-gold transition-colors">
                            <i class="fas fa-check text-gold text-[10px] group-hover/li:text-slate-950 transition-colors"></i>
                        </div>
                        <span class="group-hover/li:text-white transition-colors">{{ $misi }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-40 bg-[#060912] relative overflow-hidden">
    <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center max-w-2xl mx-auto mb-20" data-aos="fade-up">
            <span class="inline-block px-5 py-2 rounded-full bg-gold/10 text-gold text-[9px] font-black uppercase tracking-[0.4em] mb-6 border border-gold/20">The Visionaries</span>
            <h2 class="font-poppins font-black text-4xl text-white tracking-tighter">Intelligence Behind <span class="text-gold">the Service</span></h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach([
            ['AS','Ahmad Syarif','CEO & Founder', 'from-gold to-gold-dark'],
            ['RD','Rina Dewi','Operations Director', 'from-blue-600 to-blue-800'],
            ['BP','Budi Prasetyo','Strategy Lead', 'from-emerald-600 to-emerald-800'],
            ['DL','Dini Lestari','Client Experience', 'from-purple-600 to-purple-800'],
            ] as $i=>$t)
            <div class="glass-card p-10 rounded-[2.5rem] border-white/5 hover:border-gold/40 hover:-translate-y-4 transition-all duration-700 group relative overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $i*150 }}">
                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-gold/[0.02] opacity-0 group-hover:opacity-100 transition-opacity"></div>

                <div class="w-24 h-24 rounded-[2rem] bg-gradient-to-br {{ $t[3] }} flex items-center justify-center text-slate-950 text-3xl font-black mx-auto mb-8 shadow-xl group-hover:rotate-12 transition-transform duration-700 relative z-10">
                    <span class="drop-shadow-2xl">{{ $t[0] }}</span>
                </div>

                <div class="relative z-10 text-center">
                    <h4 class="text-white font-black text-xl mb-2 tracking-tighter">{{ $t[1] }}</h4>
                    <p class="text-gold text-[9px] font-black uppercase tracking-[0.3em] mb-8 opacity-80">{{ $t[2] }}</p>

                    <div class="flex justify-center gap-4">
                        <a href="#" class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-gold hover:border-gold/40 hover:bg-gold/10 transition-all duration-500">
                            <i class="fab fa-linkedin-in text-base"></i>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-gold hover:border-gold/40 hover:bg-gold/10 transition-all duration-500">
                            <i class="fab fa-instagram text-base"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Map & Location -->
<section class="py-40 bg-[#080B14] border-t border-white/5 relative overflow-hidden">
    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col lg:flex-row gap-16 lg:gap-24 items-center">
            <div class="w-full lg:w-5/12" data-aos="fade-right">
                <span class="inline-block px-5 py-2 rounded-full bg-blue-600/10 text-blue-400 text-[9px] font-black uppercase tracking-[0.4em] mb-8 border border-blue-600/20">The Epicenter</span>
                <h2 class="font-poppins font-black text-4xl text-white mb-8 leading-[1.1] tracking-tighter">Visit Our <br> <span class="text-gradient-gold text-5xl drop-shadow-xl">Siliwangi HQ</span></h2>
                <p class="text-slate-400 text-lg mb-10 leading-relaxed font-medium opacity-80">Our operational control center strategically located to ensure service speed throughout West Java.</p>
                <div class="glass-card p-8 rounded-[2.5rem] border-white/5 bg-white/[0.01] relative group">
                    <div class="absolute inset-0 bg-gold/5 opacity-0 group-hover:opacity-100 transition-opacity rounded-[2.5rem]"></div>
                    <div class="flex items-start gap-6 relative z-10">
                        <div class="w-16 h-16 rounded-2xl bg-gold/10 flex items-center justify-center text-gold text-2xl border border-gold/20 shadow-xl shrink-0"><i class="fas fa-map-marked-alt"></i></div>
                        <div>
                            <div class="text-white font-black text-base uppercase tracking-widest mb-2">Headquarters</div>
                            <p class="text-slate-400 text-base leading-relaxed font-medium">Jl. Siliwangi No. 88, <br> Bandung City, West Java</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-7/12 aspect-[16/10] rounded-[3.5rem] overflow-hidden border border-white/10 shadow-2xl relative" data-aos="fade-left">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.3540779394!2d107.5308957!3d-6.903446!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e9adf177bf8d%3A0x437398556c9d6de!2sBandung%2C%20Kota%20Bandung%2C%20Jawa%20Barat!5e0!3m2!1sid!2sid!4v1680000000000!5m2!1sid!2sid"
                    width="100%" height="100%" style="border:0;filter: grayscale(1) contrast(1.5) invert(0.9) brightness(0.7) sepia(0.2);" allowfullscreen="" loading="lazy"></iframe>
                <div class="absolute inset-0 pointer-events-none border-[20px] border-[#080B14]/80"></div>
                <!-- Interactive Map Pin Decor -->
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                    <div class="w-10 h-10 bg-gold rounded-full blur-xl animate-ping opacity-50"></div>
                    <div class="w-5 h-5 bg-gold rounded-full border-4 border-white shadow-xl relative z-10"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection