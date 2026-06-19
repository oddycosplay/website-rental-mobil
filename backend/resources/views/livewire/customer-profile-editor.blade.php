<div class="w-full">
    <form wire:submit.prevent="updateProfile" class="space-y-6">
        @if (session()->has('message'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl mb-6 text-xs font-bold uppercase tracking-widest flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i> {{ session('message') }}
            </div>
        @endif

        <div class="space-y-5">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input type="text" wire:model="name" class="w-full bg-slate-900/50 border border-white/5 rounded-2xl px-5 py-3 text-white focus:border-gold focus:ring-1 focus:ring-gold/50 outline-none transition-all placeholder:text-slate-700 text-sm">
                @error('name') <span class="text-red-400 text-[10px] font-bold uppercase tracking-wider ml-1">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nomor WhatsApp</label>
                <input type="text" wire:model="phone" class="w-full bg-slate-900/50 border border-white/5 rounded-2xl px-5 py-3 text-white focus:border-gold focus:ring-1 focus:ring-gold/50 outline-none transition-all placeholder:text-slate-700 text-sm">
                @error('phone') <span class="text-red-400 text-[10px] font-bold uppercase tracking-wider ml-1">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">NIK (KTP)</label>
                <input type="text" wire:model="nik" class="w-full bg-slate-900/50 border border-white/5 rounded-2xl px-5 py-3 text-white focus:border-gold focus:ring-1 focus:ring-gold/50 outline-none transition-all placeholder:text-slate-700 text-sm">
                @error('nik') <span class="text-red-400 text-[10px] font-bold uppercase tracking-wider ml-1">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Domisili</label>
                <textarea wire:model="address" rows="2" class="w-full bg-slate-900/50 border border-white/5 rounded-2xl px-5 py-3 text-white focus:border-gold focus:ring-1 focus:ring-gold/50 outline-none transition-all placeholder:text-slate-700 text-sm"></textarea>
                @error('address') <span class="text-red-400 text-[10px] font-bold uppercase tracking-wider ml-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Update KTP</label>
                    <label class="flex items-center justify-center w-full h-12 rounded-xl border-2 border-dashed border-white/10 hover:border-gold/40 cursor-pointer transition-all bg-slate-900/30 group">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-500 group-hover:text-gold">Pilih File</span>
                        <input type="file" wire:model="ktp_image" class="hidden">
                    </label>
                    @if($existing_ktp)
                        <div class="flex items-center justify-between px-1">
                            <span class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest">KTP Verfied</span>
                            <a href="{{ Storage::url($existing_ktp) }}" target="_blank" class="text-[9px] font-bold text-gold hover:underline uppercase tracking-widest">View</a>
                        </div>
                    @endif
                    @error('ktp_image') <span class="text-red-400 text-[10px] font-bold uppercase tracking-wider">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Update SIM A</label>
                    <label class="flex items-center justify-center w-full h-12 rounded-xl border-2 border-dashed border-white/10 hover:border-gold/40 cursor-pointer transition-all bg-slate-900/30 group">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-500 group-hover:text-gold">Pilih File</span>
                        <input type="file" wire:model="sim_image" class="hidden">
                    </label>
                    @if($existing_sim)
                        <div class="flex items-center justify-between px-1">
                            <span class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest">SIM Verified</span>
                            <a href="{{ Storage::url($existing_sim) }}" target="_blank" class="text-[9px] font-bold text-gold hover:underline uppercase tracking-widest">View</a>
                        </div>
                    @endif
                    @error('sim_image') <span class="text-red-400 text-[10px] font-bold uppercase tracking-wider">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 rounded-2xl bg-gold text-slate-900 font-black text-xs hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-gold/10 uppercase tracking-[0.2em]" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="updateProfile">Update Profile Data</span>
                    <span wire:loading wire:target="updateProfile"><i class="fas fa-spinner fa-spin mr-2"></i> Syncing...</span>
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:navigated', () => {
        Livewire.on('profileUpdated', () => {
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.8 },
                colors: ['#D4AF37', '#ffffff', '#10b981']
            });
        });
    });
</script>
@endpush
