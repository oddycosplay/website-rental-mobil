@extends('layouts.app')
@section('title', 'Contact Us – Siliwangi Rental')
@section('styles')
<style>
    .glass-contact-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 2rem;
    }
</style>
@endsection

@section('content')
<section class="relative pt-64 pb-32 overflow-hidden bg-slate-900">
    <div class="absolute top-0 right-0 w-1/3 h-1/3 bg-gold/5 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/4"></div>
    <div class="container mx-auto px-6 relative z-10 text-center max-w-3xl">
        <span class="inline-block px-4 py-1.5 rounded-full bg-gold/10 text-gold text-[10px] font-black uppercase tracking-[0.3em] mb-6 border border-gold/20" data-aos="fade-down">Contact Us</span>
        <h1 class="font-poppins font-black text-5xl md:text-6xl text-white mb-8 leading-tight" data-aos="fade-up">We Are Ready to <span class="text-gold">Help</span> You</h1>
        <p class="text-slate-400 text-lg leading-relaxed font-medium" data-aos="fade-up" data-aos-delay="100">Have questions about the fleet or need a special offer? Our team is available 24/7 to serve you.</p>
    </div>
</section>

<section class="py-32 bg-slate-900">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
            
            <!-- Contact Information -->
            <div class="lg:col-span-5 space-y-10" data-aos="fade-right">
                <div class="mb-12">
                    <h2 class="text-white font-black text-3xl mb-4">Contact <span class="text-gold">Information</span></h2>
                    <p class="text-slate-500 font-medium">Multiple ways to connect with our team.</p>
                </div>

                <div class="space-y-6">
                    @foreach([
                        ['icon' => 'fa-phone', 'title' => 'Phone', 'content' => '+62 812-3456-7890', 'link' => 'tel:+6281234567890'],
                        ['icon' => 'fa-whatsapp', 'title' => 'WhatsApp', 'content' => 'Chat with our CS', 'link' => 'https://wa.me/6281234567890'],
                        ['icon' => 'fa-envelope', 'title' => 'Official Email', 'content' => 'info@siliwangirental.com', 'link' => 'mailto:info@siliwangirental.com'],
                        ['icon' => 'fa-map-marker-alt', 'title' => 'Office Address', 'content' => 'Jl. Siliwangi No. 88, Bandung', 'link' => '#'],
                        ['icon' => 'fa-clock', 'title' => 'Operational Hours', 'content' => 'Mon - Sun: 24 Hours', 'link' => '#'],
                    ] as $item)
                    <div class="glass-contact-card p-6 flex items-center gap-6 group hover:border-gold/30 transition-all">
                        <div class="w-14 h-14 rounded-2xl bg-gold/10 text-gold flex items-center justify-center text-xl border border-gold/10 group-hover:bg-gold group-hover:text-slate-900 transition-all duration-500">
                            <i class="fas {{ $item['icon'] }}"></i>
                        </div>
                        <div>
                            <h4 class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">{{ $item['title'] }}</h4>
                            <a href="{{ $item['link'] }}" class="text-white font-bold text-lg hover:text-gold transition-colors">{{ $item['content'] }}</a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="pt-10 border-t border-white/5">
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-6">Social Media</p>
                    <div class="flex gap-4">
                        @foreach(['fab fa-instagram', 'fab fa-facebook-f', 'fab fa-tiktok', 'fab fa-youtube'] as $social)
                        <a href="#" class="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:text-gold hover:border-gold/50 transition-all text-lg">
                            <i class="{{ $social }}"></i>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-7" data-aos="fade-left">
                <div class="glass-contact-card p-12 border-white/10 relative overflow-hidden">
                    <div class="absolute -top-20 -right-20 w-60 h-60 bg-gold/5 rounded-full blur-[80px]"></div>
                    
                    <div class="relative z-10">
                        <h3 class="text-white font-black text-3xl mb-2">Send Message</h3>
                        <p class="text-slate-500 mb-10">Fill out the form below, our team will respond in less than 24 hours.</p>

                        @if(session('success'))
                        <div class="mb-8 p-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center gap-4">
                            <i class="fas fa-check-circle text-xl"></i>
                            <span class="font-bold text-sm">{{ session('success') }}</span>
                        </div>
                        @endif

                        <form action="{{ url('/contact') }}" method="POST" class="space-y-8">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-3">
                                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                                    <div class="relative group">
                                        <input type="text" name="name" required class="w-full bg-slate-900/60 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold focus:ring-1 focus:ring-gold/50 outline-none transition-all text-sm" placeholder="John Doe">
                                        <i class="fas fa-user absolute right-6 top-1/2 -translate-y-1/2 text-slate-700 group-focus-within:text-gold transition-colors text-xs"></i>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">WhatsApp Number</label>
                                    <div class="relative group">
                                        <input type="tel" name="phone" required class="w-full bg-slate-900/60 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold focus:ring-1 focus:ring-gold/50 outline-none transition-all text-sm" placeholder="08xx-xxxx-xxxx">
                                        <i class="fab fa-whatsapp absolute right-6 top-1/2 -translate-y-1/2 text-slate-700 group-focus-within:text-gold transition-colors text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Subject</label>
                                <select name="subject" class="w-full bg-slate-900/60 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold focus:ring-1 focus:ring-gold/50 outline-none transition-all text-sm appearance-none">
                                    <option value="">Select Question Category</option>
                                    <option>Vehicle Rental Information</option>
                                    <option>Partnership & Business</option>
                                    <option>Technical Support</option>
                                    <option>Others</option>
                                </select>
                            </div>

                            <div class="space-y-3">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Your Message</label>
                                <textarea name="message" required rows="5" class="w-full bg-slate-900/60 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold focus:ring-1 focus:ring-gold/50 outline-none transition-all text-sm" placeholder="Write your questions or messages here..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-5 rounded-2xl bg-gold text-slate-900 font-black text-sm uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-gold/20 flex items-center justify-center gap-3">
                                <i class="fas fa-paper-plane"></i> Send Message Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-32 bg-slate-800/20 border-t border-white/5">
    <div class="container mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto mb-20" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 rounded-full bg-blue-500/10 text-blue-400 text-[10px] font-black uppercase tracking-[0.3em] mb-6 border border-blue-500/20">Our Reach</span>
            <h2 class="font-poppins font-black text-4xl text-white">Siliwangi Rental <span class="text-gold">Branches</span></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['city' => 'Bandung', 'address' => 'Jl. Siliwangi No. 88', 'phone' => '+62 22-123-456'],
                ['city' => 'Jakarta', 'address' => 'Sudirman Central Business District', 'phone' => '+62 21-998-776'],
                ['city' => 'Bogor', 'address' => 'Pajajaran Indah V No. 12', 'phone' => '+62 251-554-321'],
            ] as $branch)
            <div class="glass-contact-card p-8 group hover:bg-gold/5 transition-all duration-500" data-aos="fade-up">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-gold/10 text-gold flex items-center justify-center border border-gold/10">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-black text-lg">{{ $branch['city'] }}</h4>
                        <p class="text-gold text-[10px] font-bold uppercase tracking-widest">Main Branch</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <p class="text-slate-400 text-sm leading-relaxed"><i class="fas fa-map-pin text-gold mr-3"></i> {{ $branch['address'] }}</p>
                    <p class="text-slate-400 text-sm"><i class="fas fa-phone-alt text-gold mr-3"></i> {{ $branch['phone'] }}</p>
                </div>
                <div class="mt-8 pt-6 border-t border-white/5">
                    <a href="#" class="text-white text-[10px] font-black uppercase tracking-widest hover:text-gold transition-colors flex items-center gap-2">
                        View on Maps <i class="fas fa-arrow-right text-[8px]"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
