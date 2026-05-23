@extends('layouts.app')
@section('title', 'FAQ – Siliwangi Rental')
@section('styles')
<style>
    .glass-faq {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 1.5rem;
    }
</style>
@endsection

@section('content')
@php 
    $faqs=[
        ['fas fa-clipboard-list','Booking Process',[
            ['How do I book a vehicle?','You can book through our website by filling out the booking form on the checkout page, or contact us directly via WhatsApp. The booking process is very easy and fast, taking only 2-3 minutes.'],
            ['Can I book same-day?','Yes, we accept same-day bookings as long as the fleet is available. However, we recommend booking at least 24 hours in advance to ensure the availability of your preferred vehicle.'],
            ['What documents do I need to prepare?','You need to prepare your ID card (original), an active driver\'s license, and for self-drive rentals, a security deposit or additional identification is required.'],
        ]],
        ['fas fa-credit-card','Payment',[
            ['What payment methods are available?','We accept bank transfers, credit/debit cards, and various digital wallets (GoPay, OVO, DANA) through our secure and trusted Midtrans gateway.'],
            ['Are there any hidden fees?','The prices listed include the basic rental cost. Additional fees only apply for driver services, vehicle delivery to specific locations, or fuel usage according to policy.'],
        ]],
        ['fas fa-car','Rental Policy',[
            ['Can I take the vehicle out of town?','Yes, we allow out-of-town trips with specific terms and conditions. Please inform our team of your route during the booking process.'],
            ['What if there is damage?','Contact our 24/7 emergency assistance team immediately. All our units are insured, and our team will provide step-by-step instructions.'],
        ]],
    ];
@endphp

<div x-data="{ 
    search: '',
    faqs: [
        @foreach($faqs as $cat)
            @foreach($cat[2] as $qa)
                { q: '{{ str_replace("'", "\\'", $qa[0]) }}', a: '{{ str_replace("'", "\\'", $qa[1]) }}', cat: '{{ $cat[1] }}' },
            @endforeach
        @endforeach
    ],
    get filteredFaqs() {
        if (!this.search) return this.faqs;
        return this.faqs.filter(f => 
            f.q.toLowerCase().includes(this.search.toLowerCase()) || 
            f.a.toLowerCase().includes(this.search.toLowerCase())
        );
    }
}">
<section class="relative pt-64 pb-32 overflow-hidden bg-slate-900">
    <div class="absolute top-0 left-0 w-1/3 h-1/3 bg-gold/5 rounded-full blur-[120px] -translate-y-1/2 -translate-x-1/4"></div>
    <div class="container mx-auto px-6 relative z-10 text-center max-w-3xl">
        <span class="inline-block px-4 py-1.5 rounded-full bg-gold/10 text-gold text-[10px] font-black uppercase tracking-[0.3em] mb-6 border border-gold/20" data-aos="fade-down">Help Center</span>
        <h1 class="font-poppins font-black text-5xl md:text-6xl text-white mb-8 leading-tight" data-aos="fade-up">Frequently Asked <span class="text-gold">Questions</span></h1>
        <p class="text-slate-400 text-lg leading-relaxed font-medium mb-12" data-aos="fade-up" data-aos-delay="100">Find quick answers to your common questions regarding our booking process, payment, and fleet services.</p>
        
        <div class="relative max-w-xl mx-auto" data-aos="fade-up" data-aos-delay="200">
            <input type="text" x-model="search" placeholder="Search for questions..." class="w-full bg-white/5 border border-white/10 rounded-2xl px-8 py-5 text-white focus:border-gold focus:ring-1 focus:ring-gold/50 outline-none transition-all pr-16 backdrop-blur-xl">
            <div class="absolute right-6 top-1/2 -translate-y-1/2 text-gold">
                <i class="fas fa-search"></i>
            </div>
        </div>
    </div>
</section>

<section class="py-32 bg-slate-900">
    <div class="container mx-auto px-6 max-w-4xl">
        <!-- Search Results -->
        <template x-if="search">
            <div class="mb-20">
                <div class="flex items-center gap-4 mb-8 px-4">
                    <h2 class="text-white font-black text-xl uppercase tracking-widest">Search Results</h2>
                    <div class="flex-1 h-[1px] bg-white/5"></div>
                </div>
                <div class="space-y-4">
                    <template x-for="(f, i) in filteredFaqs" :key="i">
                        <div class="glass-faq overflow-hidden transition-all duration-300 border-gold/30 bg-gold/5">
                            <div class="px-8 py-6">
                                <span class="text-[8px] font-black text-gold uppercase tracking-widest mb-2 block" x-text="f.cat"></span>
                                <h3 class="text-white font-bold text-lg mb-4" x-text="f.q"></h3>
                                <p class="text-slate-400 text-lg leading-relaxed pt-4 border-t border-white/5" x-text="f.a"></p>
                            </div>
                        </div>
                    </template>
                    <template x-if="filteredFaqs.length === 0">
                        <div class="text-center py-12 glass-faq">
                            <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">No questions found.</p>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        <!-- Static FAQ List (hidden when searching) -->
        <div x-show="!search" class="space-y-12">
            @foreach($faqs as $catIndex => $cat)
            <div data-aos="fade-up" data-aos-delay="{{ $catIndex * 100 }}">
                <div class="flex items-center gap-4 mb-8 px-4">
                    <div class="w-10 h-10 rounded-xl bg-gold/10 text-gold flex items-center justify-center border border-gold/10 text-sm">
                        <i class="{{ $cat[0] }}"></i>
                    </div>
                    <h2 class="text-white font-black text-xl uppercase tracking-widest">{{ $cat[1] }}</h2>
                    <div class="flex-1 h-[1px] bg-white/5"></div>
                </div>

                <div class="space-y-4" x-data="{ active: null }">
                    @foreach($cat[2] as $i => $qa)
                    <div class="glass-faq overflow-hidden transition-all duration-300" 
                         :class="{ 'border-gold/30 bg-gold/5': active === {{ $i }} }">
                        <button 
                            @click="active = active === {{ $i }} ? null : {{ $i }}"
                            class="w-full text-left px-8 py-6 flex items-center justify-between group">
                            <span class="text-white font-bold text-lg group-hover:text-gold transition-colors pr-8">{{ $qa[0] }}</span>
                            <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-slate-500 transition-all duration-500"
                                 :class="{ 'rotate-180 text-gold bg-gold/20': active === {{ $i }} }">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </button>
                        <div 
                            x-show="active === {{ $i }}" 
                            x-collapse
                            x-cloak>
                            <div class="px-8 pb-8 text-slate-400 text-lg leading-relaxed border-t border-white/5 pt-6">
                                {{ $qa[1] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-48 p-12 glass-faq border-gold/20 text-center relative overflow-hidden" data-aos="zoom-in">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-gold/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-500/5 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <h3 class="text-white font-black text-3xl mb-4">Still Have Questions?</h3>
                <p class="text-slate-400 text-lg mb-10 max-w-xl mx-auto">If you didn\'t find the answer you were looking for, our team is ready to help you 24/7 via WhatsApp.</p>
                <a href="https://wa.me/628973816530" target="_blank" class="inline-flex items-center gap-3 px-10 py-5 rounded-2xl bg-gold text-slate-900 font-black text-sm uppercase tracking-widest hover:scale-105 transition-all shadow-xl shadow-gold/20">
                    <i class="fab fa-whatsapp text-lg"></i> Contact Customer Service
                </a>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
