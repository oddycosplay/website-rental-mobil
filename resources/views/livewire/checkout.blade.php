<div class="pt-28 pb-20 bg-[#0A0F1C] min-h-screen text-slate-200 font-poppins selection:bg-gold/30">
    @section('title', 'Checkout Booking – Siliwangi Rental')
    {{-- Background Accents --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-gold/5 rounded-full blur-[120px]"></div>
        <div class="absolute top-[20%] -right-[10%] w-[30%] h-[30%] bg-blue-500/5 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[40%] h-[40%] bg-gold/5 rounded-full blur-[150px]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Header & Progress --}}
        <div class="mb-12">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                <div data-aos="fade-right">
                    <nav class="flex items-center gap-2 text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-4">
                        <a href="{{ url('/') }}" class="hover:text-gold transition-colors">Home</a>
                        <i class="fas fa-chevron-right text-[8px]"></i>
                        <a href="{{ url('/cars') }}" class="hover:text-gold transition-colors">Fleet</a>
                        <i class="fas fa-chevron-right text-[8px]"></i>
                        <span class="text-gold">Checkout</span>
                    </nav>
                    <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight leading-none">
                        Booking <span class="text-gold">Checkout</span>
                    </h1>
                </div>

                {{-- Horizontal Steps (Desktop) --}}
                <div class="hidden lg:flex items-center gap-2 bg-white/5 backdrop-blur-xl border border-white/10 p-1.5 rounded-2xl" data-aos="fade-left">
                    @foreach([1 => 'Time', 2 => 'Data', 3 => 'Files', 4 => 'Options', 5 => 'Pay'] as $s => $label)
                    <div class="flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 {{ $step == $s ? 'bg-gold text-slate-950 shadow-lg shadow-gold/20' : ($step > $s ? 'text-emerald-400 bg-emerald-500/5' : 'text-slate-500') }}">
                        <span class="w-5 h-5 rounded-lg flex items-center justify-center text-[10px] font-black {{ $step == $s ? 'bg-slate-950/20' : ($step > $s ? 'bg-emerald-500/10' : 'bg-white/5') }}">
                            @if($step > $s) <i class="fas fa-check"></i> @else {{ $s }} @endif
                        </span>
                        <span class="text-[10px] font-black uppercase tracking-widest">{{ $label }}</span>
                    </div>
                    @if(!$loop->last)
                    <div class="w-4 h-px bg-white/10"></div>
                    @endif
                    @endforeach
                </div>
            </div>

            {{-- Mobile Steps --}}
            <div class="lg:hidden flex justify-between items-center bg-white/5 border border-white/10 p-4 rounded-2xl mb-8">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Step {{ $step }} of 5</span>
                <span class="text-xs font-black text-white">{{ [1 => 'Select Time', 2 => 'Identity', 3 => 'Upload Files', 4 => 'Service Detail', 5 => 'Payment'][$step] }}</span>
                <div class="flex gap-1">
                    @for($i=1; $i<=5; $i++)
                        <div class="w-2 h-1 rounded-full {{ $step >= $i ? 'bg-gold' : 'bg-white/10' }}">
                </div>
                @endfor
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="flex flex-col lg:flex-row gap-8 items-start">

        {{-- ── LEFT: Form Area ── --}}
        <div class="w-full lg:w-[60%] xl:w-[65%] space-y-6">

            @if(session()->has('error'))
            <div class="bg-red-500/10 text-red-400 p-5 rounded-2xl border border-red-500/20 flex items-center gap-4 animate-shake">
                <i class="fas fa-circle-exclamation"></i>
                <p class="text-xs font-bold">{{ session('error') }}</p>
            </div>
            @endif

            {{-- FORM CARDS --}}
            <div class="bg-white/5 backdrop-blur-3xl rounded-[2rem] border border-white/10 shadow-2xl overflow-hidden min-h-[400px]">

                {{-- Global Validation Errors --}}
                @if($errors->any())
                <div class="bg-red-500/10 border-b border-red-500/20 p-6 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center text-red-500 flex-shrink-0">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <p class="text-sm font-black text-red-400 uppercase tracking-widest mb-1">Errors Occurred</p>
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                            <li class="text-[11px] font-bold text-red-300/80">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                {{-- Step 1: Formulir Pemesanan Rental --}}
                @if($step == 1)
                    <div class="p-8 md:p-12" data-aos="fade-up">
                        <div class="flex items-center gap-4 mb-10">
                            <div class="w-12 h-12 rounded-2xl bg-gold/10 text-gold flex items-center justify-center text-xl shadow-inner">
                                <i class="fas fa-calendar-range"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-white">Formulir Pemesanan Rental</h3>
                                <p class="text-xs text-slate-500 font-medium mt-1">Tentukan tanggal, lokasi, dan kategori kebutuhan Anda.</p>
                            </div>
                        </div>

                        {{-- Date --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Tanggal Penjemputan</label>
                                <div class="relative group">
                                    <input type="date" wire:model.live="pickup_date" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold focus:ring-1 focus:ring-gold/20 outline-none transition-all font-medium [color-scheme:dark]">
                                    <i class="fas fa-calendar absolute right-6 top-1/2 -translate-y-1/2 text-slate-600 group-focus-within:text-gold transition-colors pointer-events-none"></i>
                                </div>
                                @error('pickup_date') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Tanggal Pengembalian</label>
                                <div class="relative group">
                                    <input type="date" wire:model.live="return_date" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold focus:ring-1 focus:ring-gold/20 outline-none transition-all font-medium [color-scheme:dark]">
                                    <i class="fas fa-calendar-check absolute right-6 top-1/2 -translate-y-1/2 text-slate-600 group-focus-within:text-gold transition-colors pointer-events-none"></i>
                                </div>
                                @error('return_date') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- 24 Hour Info --}}
                        <div class="mt-8 p-5 rounded-2xl bg-gold/5 border border-gold/10 flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-gold/10 text-gold flex items-center justify-center text-sm flex-shrink-0"><i class="fas fa-info"></i></div>
                            <div class="text-xs text-slate-400 leading-relaxed">
                                <p class="font-bold text-white mb-1">Penting untuk diketahui:</p>
                                <p>Satu hari sewa dihitung sebagai <strong class="text-gold">24 jam</strong>. Pengembalian terlambat akan dikenakan biaya tambahan sesuai dengan ketentuan yang berlaku.</p>
                            </div>
                        </div>

                        {{-- Antar-Jemput Service Mode --}}
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-white/5 pt-8">
                            {{-- Layanan Pengantaran (Awal Sewa) --}}
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Layanan Pengantaran (Awal Sewa)</label>
                                <div class="relative">
                                    <select wire:model.live="delivery_type" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium appearance-none cursor-pointer">
                                        <option value="none" class="bg-slate-900">Ambil Sendiri di Garasi (Gratis)</option>
                                        <option value="standard" class="bg-slate-900">Diantar ke Lokasi (Radius 10km+) - Rp 100.000</option>
                                        <option value="airport" class="bg-slate-900">Diantar ke Bandara (Airport) - Rp 200.000</option>
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-600 pointer-events-none"></i>
                                </div>
                                @error('delivery_type') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror

                                @if($delivery_type !== 'none')
                                <div class="space-y-2 mt-3">
                                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Pengantaran Mobil</label>
                                    <input type="text" wire:model="pickup_location" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium placeholder:text-slate-700" placeholder="Masukkan alamat pengantaran lengkap...">
                                    @error('pickup_location') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                                </div>
                                @endif
                            </div>

                            {{-- Layanan Penjemputan (Akhir Sewa) --}}
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Layanan Penjemputan (Akhir Sewa)</label>
                                <div class="relative">
                                    <select wire:model.live="pickup_type" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium appearance-none cursor-pointer">
                                        <option value="none" class="bg-slate-900">Kembalikan Sendiri ke Garasi (Gratis)</option>
                                        <option value="standard" class="bg-slate-900">Dijemput di Lokasi (Radius 10km+) - Rp 100.000</option>
                                        <option value="airport" class="bg-slate-900">Dijemput di Bandara (Airport) - Rp 200.000</option>
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-600 pointer-events-none"></i>
                                </div>
                                @error('pickup_type') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror

                                @if($pickup_type !== 'none')
                                <div class="space-y-2 mt-3">
                                    <label class="text-[9px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Penjemputan Mobil</label>
                                    <input type="text" wire:model="return_location" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium placeholder:text-slate-700" placeholder="Masukkan alamat penjemputan lengkap...">
                                    @error('return_location') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Ojek Online Service --}}
                        <div class="mt-8 pt-8 border-t border-white/5 space-y-4">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Layanan Pengantar Ojek Online (Grab, Gojek, dll)</label>
                            <div class="relative">
                                <select wire:model.live="ojol_service" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium appearance-none cursor-pointer">
                                    <option value="none" class="bg-slate-900">Tidak Menggunakan Layanan Ojol (Rp 0)</option>
                                    <option value="gojek" class="bg-slate-900">Gojek (Rp 25.000)</option>
                                    <option value="grab" class="bg-slate-900">Grab (Rp 25.000)</option>
                                    <option value="maxim" class="bg-slate-900">Maxim (Rp 20.000)</option>
                                    <option value="lainnya" class="bg-slate-900">Lainnya (Rp 25.000)</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-600 pointer-events-none"></i>
                            </div>
                            @error('ojol_service') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Category + Branch --}}
                        <div class="mt-8 pt-8 border-t border-white/5 grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Tipe Sewa</label>
                                <div class="relative">
                                    <select wire:model.live="rental_type" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium appearance-none cursor-pointer">
                                        <option value="daily" class="bg-slate-900">Daily</option>
                                        <option value="monthly" class="bg-slate-900">Monthly</option>
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-600 pointer-events-none"></i>
                                </div>
                                @error('rental_type') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Kategori Sewa</label>
                                <div class="relative">
                                    <select wire:model.live="rental_category" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium appearance-none cursor-pointer">
                                        <option value="pribadi" class="bg-slate-900">Pribadi</option>
                                        <option value="perusahaan" class="bg-slate-900">Perusahaan</option>
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-600 pointer-events-none"></i>
                                </div>
                                @error('rental_category') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Step 2: Identity --}}
                @if($step == 2)
                <div class="p-8 md:p-12" data-aos="fade-up">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 rounded-2xl bg-gold/10 text-gold flex items-center justify-center text-xl shadow-inner">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-white">Data Penyewa</h3>
                            <p class="text-xs text-slate-500 font-medium mt-1">Pastikan data sesuai dengan identitas resmi Anda (KTP).</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Nama Lengkap Sesuai KTP --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap Sesuai KTP</label>
                            <input type="text" wire:model="name" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium placeholder:text-slate-700" placeholder="e.g. John Doe">
                            @error('name') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- NIK KTP --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">NIK KTP</label>
                            <input type="text" wire:model="nik" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium placeholder:text-slate-700" placeholder="16 digit NIK KTP">
                            @error('nik') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- NIK SIM --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">NIK SIM</label>
                            <input type="text" wire:model="sim_number" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium placeholder:text-slate-700" placeholder="Nomor SIM Anda">
                            @error('sim_number') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- NIK Kartu Keluarga --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">NIK Kartu Keluarga</label>
                            <input type="text" wire:model="no_kk" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium placeholder:text-slate-700" placeholder="16 digit No Kartu Keluarga">
                            @error('no_kk') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- No ID Card --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nomor ID Card (Karyawan/Pelajar/dll)</label>
                            <input type="text" wire:model="nip_nim" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium placeholder:text-slate-700" placeholder="Nomor ID Card / NIP / NIM">
                            @error('nip_nim') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Status Pekerjaan --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Status Pekerjaan</label>
                            <input type="text" wire:model="pekerjaan" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium placeholder:text-slate-700" placeholder="e.g. Pegawai Swasta, PNS, Mahasiswa, dll">
                            @error('pekerjaan') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Nomer Telepon --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nomer Telepon (WhatsApp)</label>
                            <div class="relative">
                                <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-sm border-r border-white/10 pr-3">+62</span>
                                <input type="text" wire:model="phone" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl pl-20 pr-6 py-4 text-white focus:border-gold outline-none transition-all font-medium placeholder:text-slate-700" placeholder="8123456xxx">
                            </div>
                            @error('phone') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Email --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Email</label>
                            <input type="email" wire:model="email" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium placeholder:text-slate-700" placeholder="johndoe@email.com">
                            @error('email') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                        </div>

                        {{-- Alamat Rumah Sesuai KTP --}}
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Rumah Sesuai KTP</label>
                            <textarea wire:model="address" rows="3" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium placeholder:text-slate-700 resize-none" placeholder="Masukkan alamat rumah lengkap Anda sesuai KTP..."></textarea>
                            @error('address') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                @endif

                {{-- Step 3: Documents --}}
                @if($step == 3)
                <div class="p-8 md:p-12" data-aos="fade-up" x-data="{ 
                    showSelfieModal: false, 
                    useCamera: false,
                    stream: null,
                    startCamera() {
                        this.useCamera = true;
                        navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 } })
                            .then(s => {
                                this.stream = s;
                                $refs.video.srcObject = s;
                                $refs.video.play();
                            })
                            .catch(err => {
                                alert('Tidak dapat mengakses kamera: ' + err.message);
                                this.useCamera = false;
                            });
                    },
                    stopCamera() {
                        if (this.stream) {
                            this.stream.getTracks().forEach(track => track.stop());
                            this.stream = null;
                        }
                        this.useCamera = false;
                    },
                    capture() {
                        const video = $refs.video;
                        const canvas = document.createElement('canvas');
                        canvas.width = video.videoWidth || 640;
                        canvas.height = video.videoHeight || 480;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                        const dataUrl = canvas.toDataURL('image/jpeg');
                        
                        // Set base64 string directly to Livewire selfie_image
                        @this.set('selfie_image', dataUrl);
                        
                        this.stopCamera();
                        this.showSelfieModal = false;
                    }
                }">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 rounded-2xl bg-gold/10 text-gold flex items-center justify-center text-xl shadow-inner">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-white">Verifikasi Dokumen</h3>
                            <p class="text-xs text-slate-500 font-medium mt-1">Unggah dokumen resmi Anda untuk memverifikasi pesanan sewa secara aman.</p>
                        </div>
                    </div>

                    {{-- Main Grid --}}
                    <div class="space-y-8">
                        {{-- 1. Selfie Card --}}
                        <div class="group">
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 block">Foto Selfie Penyewa <span class="text-red-500">*</span></label>
                            <div @click="showSelfieModal = true" class="relative min-h-[220px] rounded-3xl bg-slate-950 border-2 border-dashed border-white/10 overflow-hidden flex flex-col items-center justify-center hover:border-gold/50 transition-all cursor-pointer p-6">
                                @if($selfie_image)
                                    @if(is_string($selfie_image) && str_starts_with($selfie_image, 'data:image'))
                                        <img src="{{ $selfie_image }}" class="absolute inset-0 w-full h-full object-cover">
                                    @elseif($selfie_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                                        <img src="{{ $selfie_image->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                                    @endif
                                    <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="px-4 py-2 bg-white text-slate-950 rounded-xl text-[10px] font-black uppercase">Ambil Ulang Foto Selfie</span>
                                    </div>
                                @else
                                    <div class="text-center text-slate-700 group-hover:text-gold transition-colors">
                                        <div class="w-16 h-16 rounded-full bg-slate-900 flex items-center justify-center mx-auto mb-3 shadow-lg">
                                            <i class="fas fa-camera-retro text-2xl"></i>
                                        </div>
                                        <p class="text-[10px] font-black uppercase tracking-widest">Ambil Selfie / Unggah File</p>
                                        <p class="text-[9px] text-slate-500 mt-1">Klik untuk membuka opsi kamera atau unggah file</p>
                                    </div>
                                @endif
                            </div>
                            @error('selfie_image') <span class="text-red-500 text-[10px] font-bold mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Dokumen Lainnya Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- KTP --}}
                            <div class="group">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 block">Foto KTP Asli <span class="text-red-500">*</span></label>
                                <div class="relative aspect-video rounded-3xl bg-slate-950 border-2 border-dashed border-white/10 overflow-hidden flex items-center justify-center hover:border-gold/50 transition-all cursor-pointer">
                                    @if($ktp_image)
                                        <img src="{{ $ktp_image->temporaryUrl() }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="px-4 py-2 bg-white text-slate-950 rounded-xl text-[10px] font-black uppercase">Ubah Foto</span>
                                        </div>
                                    @else
                                        <div class="text-center text-slate-700 group-hover:text-gold transition-colors">
                                            <i class="fas fa-address-card text-4xl mb-3"></i>
                                            <p class="text-[9px] font-black uppercase tracking-widest">Klik untuk Unggah Foto KTP</p>
                                        </div>
                                    @endif
                                    <input type="file" wire:model="ktp_image" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                                @error('ktp_image') <span class="text-red-500 text-[10px] font-bold mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- SIM --}}
                            <div class="group">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 block">Foto SIM Asli <span class="text-red-500">*</span></label>
                                <div class="relative aspect-video rounded-3xl bg-slate-950 border-2 border-dashed border-white/10 overflow-hidden flex items-center justify-center hover:border-gold/50 transition-all cursor-pointer">
                                    @if($sim_image)
                                        <img src="{{ $sim_image->temporaryUrl() }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="px-4 py-2 bg-white text-slate-950 rounded-xl text-[10px] font-black uppercase">Ubah Foto</span>
                                        </div>
                                    @else
                                        <div class="text-center text-slate-700 group-hover:text-gold transition-colors">
                                            <i class="fas fa-id-badge text-4xl mb-3"></i>
                                            <p class="text-[9px] font-black uppercase tracking-widest">Klik untuk Unggah Foto SIM</p>
                                        </div>
                                    @endif
                                    <input type="file" wire:model="sim_image" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                                @error('sim_image') <span class="text-red-500 text-[10px] font-bold mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Kartu Keluarga --}}
                            <div class="group">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 block">Foto Kartu Keluarga <span class="text-red-500">*</span></label>
                                <div class="relative aspect-video rounded-3xl bg-slate-950 border-2 border-dashed border-white/10 overflow-hidden flex items-center justify-center hover:border-gold/50 transition-all cursor-pointer">
                                    @if($kk_image)
                                        <img src="{{ $kk_image->temporaryUrl() }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="px-4 py-2 bg-white text-slate-950 rounded-xl text-[10px] font-black uppercase">Ubah Foto</span>
                                        </div>
                                    @else
                                        <div class="text-center text-slate-700 group-hover:text-gold transition-colors">
                                            <i class="fas fa-people-roof text-4xl mb-3"></i>
                                            <p class="text-[9px] font-black uppercase tracking-widest">Klik untuk Unggah Kartu Keluarga</p>
                                        </div>
                                    @endif
                                    <input type="file" wire:model="kk_image" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                                @error('kk_image') <span class="text-red-500 text-[10px] font-bold mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- ID Card --}}
                            <div class="group">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 block">Foto ID Card (Karyawan/Pelajar) <span class="text-red-500">*</span></label>
                                <div class="relative aspect-video rounded-3xl bg-slate-950 border-2 border-dashed border-white/10 overflow-hidden flex items-center justify-center hover:border-gold/50 transition-all cursor-pointer">
                                    @if($id_card_image)
                                        <img src="{{ $id_card_image->temporaryUrl() }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="px-4 py-2 bg-white text-slate-950 rounded-xl text-[10px] font-black uppercase">Ubah Foto</span>
                                        </div>
                                    @else
                                        <div class="text-center text-slate-700 group-hover:text-gold transition-colors">
                                            <i class="fas fa-id-card-clip text-4xl mb-3"></i>
                                            <p class="text-[9px] font-black uppercase tracking-widest">Klik untuk Unggah Foto ID Card</p>
                                        </div>
                                    @endif
                                    <input type="file" wire:model="id_card_image" class="absolute inset-0 opacity-0 cursor-pointer">
                                </div>
                                @error('id_card_image') <span class="text-red-500 text-[10px] font-bold mt-2 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Premium Selfie Modal Popup --}}
                    <div x-show="showSelfieModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-xl transition-all" x-transition x-cloak>
                        <div class="relative w-full max-w-lg bg-slate-900 border border-white/10 rounded-3xl overflow-hidden shadow-2xl p-8 space-y-6" @click.away="stopCamera(); showSelfieModal = false">
                            {{-- Header --}}
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-lg font-black text-white">Sumber Foto Selfie</h4>
                                    <p class="text-xs text-slate-400 mt-1">Pilih metode pengambilan foto selfie Anda.</p>
                                </div>
                                <button type="button" @click="stopCamera(); showSelfieModal = false" class="text-slate-500 hover:text-white transition-colors">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>

                            {{-- Live Camera --}}
                            <div x-show="useCamera" class="space-y-4">
                                <div class="relative aspect-video rounded-2xl bg-black overflow-hidden border border-white/10">
                                    <video x-ref="video" class="w-full h-full object-cover transform -scale-x-100"></video>
                                </div>
                                <div class="flex gap-4">
                                    <button type="button" @click="capture()" class="flex-1 py-4 bg-gold hover:bg-gold-dark text-slate-950 rounded-2xl text-xs font-black uppercase tracking-wider transition-all shadow-lg hover:shadow-gold/20">
                                        <i class="fas fa-camera mr-2"></i> Ambil Foto
                                    </button>
                                    <button type="button" @click="stopCamera()" class="px-6 py-4 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl text-xs font-black uppercase tracking-wider transition-all">
                                        Batal
                                    </button>
                                </div>
                            </div>

                            {{-- Selection Grid --}}
                            <div x-show="!useCamera" class="grid grid-cols-2 gap-6">
                                {{-- Option 1: Camera --}}
                                <div @click="startCamera()" class="p-6 text-center rounded-2xl border-2 border-white/5 bg-slate-950/40 hover:border-gold hover:bg-gold/5 transition-all cursor-pointer group flex flex-col items-center justify-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-gold/10 text-gold flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                                        <i class="fas fa-video"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-white">Buka Kamera</p>
                                        <p class="text-[9px] text-slate-500 mt-0.5">Gunakan kamera depan/webcam</p>
                                    </div>
                                </div>

                                {{-- Option 2: Upload File --}}
                                <div @click="$refs.selfieFileInput.click()" class="p-6 text-center rounded-2xl border-2 border-white/5 bg-slate-950/40 hover:border-gold hover:bg-gold/5 transition-all cursor-pointer group flex flex-col items-center justify-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                                        <i class="fas fa-file-arrow-up"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-white">Unggah File</p>
                                        <p class="text-[9px] text-slate-500 mt-0.5">Pilih dari dokumen/galeri</p>
                                    </div>
                                    <input type="file" x-ref="selfieFileInput" wire:model="selfie_image" class="hidden" @change="showSelfieModal = false">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Step 4: Driver Options & Notes --}}
                @if($step == 4)
                <div class="p-8 md:p-12" data-aos="fade-up">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 rounded-2xl bg-gold/10 text-gold flex items-center justify-center text-xl shadow-inner">
                            <i class="fas fa-steering-wheel"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-white">pilih driver atau lepas kunci</h3>
                            <p class="text-xs text-slate-500 font-medium mt-1">Pilih driver atau lepas kunci.</p>
                        </div>
                    </div>

                    {{-- Driver Toggle --}}
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Mode Sewa</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer group">
                                <input type="radio" wire:model.live="with_driver" value="0" class="peer sr-only">
                                <div class="py-8 text-center rounded-2xl border-2 border-white/5 bg-slate-900/30 peer-checked:border-gold peer-checked:bg-gold/10 transition-all flex flex-col items-center gap-3 group-hover:bg-white/5">
                                    <div class="w-14 h-14 rounded-2xl bg-white/5 peer-checked:bg-gold/10 flex items-center justify-center">
                                        <i class="fas fa-key text-2xl text-slate-500 peer-checked:text-gold"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-white">Lepas Kunci</p>
                                        <p class="text-[9px] text-slate-500 mt-0.5">Anda yang menyetir</p>
                                    </div>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" wire:model.live="with_driver" value="1" class="peer sr-only">
                                <div class="py-8 text-center rounded-2xl border-2 border-white/5 bg-slate-900/30 peer-checked:border-gold peer-checked:bg-gold/10 transition-all flex flex-col items-center gap-3 group-hover:bg-white/5">
                                    <div class="w-14 h-14 rounded-2xl bg-white/5 peer-checked:bg-gold/10 flex items-center justify-center">
                                        <i class="fas fa-user-tie text-2xl text-slate-500 peer-checked:text-gold"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-white">Dengan Driver</p>
                                        <p class="text-[9px] text-slate-500 mt-0.5">Driver profesional kami</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('with_driver') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Driver fee & selection info --}}
                    @if($with_driver)
                    <div class="mt-6 p-5 rounded-2xl bg-emerald-500/5 border border-emerald-500/10 flex items-center gap-4 animate-fade-in">
                        <i class="fas fa-circle-check text-emerald-400 text-xl"></i>
                        <p class="text-xs text-slate-400">Biaya Driver Rp {{ number_format($driver_fee, 0, ',', '.') }} /hari sudah termasuk dalam rincian pembayaran.</p>
                    </div>

                    @auth
                    <div class="mt-6 space-y-3 animate-fade-in">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Pilih Driver <span class="text-slate-600 normal-case font-medium">(opsional)</span></label>
                        <div class="relative">
                            <select wire:model.live="selected_driver_id" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium appearance-none cursor-pointer">
                                <option value="" class="bg-slate-900">-- Pilih Driver Pilihan Anda --</option>
                                @foreach(\App\Models\Driver::active()->get() as $driver)
                                    <option value="{{ $driver->id }}" class="bg-slate-900">{{ $driver->name }} (Rating: {{ $driver->rating ?? '5.0' }})</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-600 pointer-events-none"></i>
                        </div>
                        <p class="text-[9px] text-slate-500 ml-1">Hanya tersedia untuk member terdaftar. Kami akan memprioritaskan driver pilihan Anda.</p>
                        @error('selected_driver_id') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                    </div>
                    @endauth
                    @else
                    {{-- Lepas Kunci Destination --}}
                    <div class="mt-6 p-6 rounded-3xl bg-white/5 border border-white/10 space-y-6 animate-fade-in">
                        <div>
                            <h4 class="text-xs font-black text-gold uppercase tracking-widest">Tujuan Kota Yang Ingin Disewa</h4>
                            <p class="text-[10px] text-slate-500 mt-1">Tentukan jangkauan perjalanan sewa lepas kunci Anda.</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer group">
                                <input type="radio" wire:model.live="dest_type" value="jabotabek" class="peer sr-only">
                                <div class="py-4 text-center rounded-xl border border-white/5 bg-slate-900/30 peer-checked:border-gold peer-checked:bg-gold/10 transition-all flex flex-col items-center gap-1 group-hover:bg-white/5">
                                    <p class="text-xs font-black text-white">Dalam JABODETABEK</p>
                                    <p class="text-[8px] text-slate-500">Jakarta, Bogor, Depok, Tangerang, Bekasi</p>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" wire:model.live="dest_type" value="luar_jabotabek" class="peer sr-only">
                                <div class="py-4 text-center rounded-xl border border-white/5 bg-slate-900/30 peer-checked:border-gold peer-checked:bg-gold/10 transition-all flex flex-col items-center gap-1 group-hover:bg-white/5">
                                    <p class="text-xs font-black text-white">Luar JABODETABEK</p>
                                    <p class="text-[8px] text-slate-500">Jawa Barat, Tengah, Timur, Bali, dll</p>
                                </div>
                            </label>
                        </div>
                        @error('dest_type') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror

                        @if($dest_type === 'luar_jabotabek')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-white/5 animate-fade-in">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Provinsi / Wilayah Tujuan</label>
                                <div class="relative">
                                    <select wire:model.live="dest_region" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium appearance-none cursor-pointer">
                                        <option value="jabar" class="bg-slate-900">Jawa Barat</option>
                                        <option value="jateng" class="bg-slate-900">Jawa Tengah</option>
                                        <option value="diy" class="bg-slate-900">D.I. Yogyakarta</option>
                                        <option value="jatim" class="bg-slate-900">Jawa Timur & Madura</option>
                                        <option value="banten" class="bg-slate-900">Banten (Luar Kota)</option>
                                        <option value="bali" class="bg-slate-900">Bali</option>
                                    </select>
                                    <i class="fas fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-slate-600 pointer-events-none"></i>
                                </div>
                                @error('dest_region') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Kota Tujuan <span class="text-red-500">*</span></label>
                                <input type="text" wire:model.live="dest_city" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium placeholder:text-slate-700" placeholder="e.g. Tasikmalaya, Semarang, Surabaya">
                                @error('dest_city') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Dynamic Minimum Days Info badge depending on region --}}
                        <div class="p-4 rounded-xl bg-gold/5 border border-gold/10 flex items-start gap-3 animate-pulse">
                            <i class="fas fa-circle-info text-gold text-sm mt-0.5"></i>
                            <div class="text-[10px] text-slate-400 leading-relaxed">
                                <p class="font-bold text-white">Kebijakan Wilayah:</p>
                                <p class="mt-0.5">
                                    @if($dest_region === 'jabar')
                                        Jawa Barat: Minimum 1 hari sewa (khusus <strong class="text-gold">Tasikmalaya & Pangandaran</strong> minimum <strong class="text-gold">2 hari</strong> sewa).
                                    @elseif($dest_region === 'jateng')
                                        Jawa Tengah: Minimum <strong class="text-gold">3 hari</strong> sewa.
                                    @elseif($dest_region === 'diy')
                                        D.I. Yogyakarta: Minimum <strong class="text-gold">3 hari</strong> sewa.
                                    @elseif($dest_region === 'jatim')
                                        Jawa Timur & Madura: Minimum <strong class="text-gold">5 hari</strong> sewa.
                                    @elseif($dest_region === 'bali')
                                        Bali: Minimum <strong class="text-gold">7 hari</strong> sewa.
                                    @else
                                        Banten: Minimum 1 hari sewa.
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Biaya Ojol Antar-Jemput --}}
                    <div class="mt-8 space-y-3">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Biaya Ojol Antar-Jemput <span class="text-slate-600 normal-case font-medium">(Opsional - Bisa diinput Pelanggan atau Admin)</span></label>
                        <div class="relative group">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xs pr-3 border-r border-white/10">Rp</span>
                            <input type="number" wire:model.live="ojol_fee" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl pl-16 pr-6 py-4 text-white focus:border-gold outline-none transition-all font-medium placeholder:text-slate-700" placeholder="0">
                        </div>
                        <p class="text-[9px] text-slate-500 ml-1">Masukkan perkiraan biaya ojek online untuk penjemputan/pengantaran staf kami, jika diperlukan.</p>
                        @error('ojol_fee') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Additional Notes --}}
                    <div class="mt-8 space-y-3">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">catatan <span class="text-slate-600 normal-case font-medium">(opsional)</span></label>
                        <textarea wire:model="additional_notes" rows="4" class="w-full bg-slate-900/50 border border-white/10 rounded-2xl px-6 py-4 text-white focus:border-gold outline-none transition-all font-medium placeholder:text-slate-700 resize-none" placeholder="e.g. Tolong siapkan kursi bayi, atau saya akan tiba pukul 14.00..."></textarea>
                    </div>
                </div>
                @endif

                {{-- Step 5: Summary & Confirmation --}}
                @if($step == 5)
                <div class="p-8 md:p-12" data-aos="fade-up">
                    <div class="flex items-center gap-4 mb-10">
                        <div class="w-12 h-12 rounded-2xl bg-gold/10 text-gold flex items-center justify-center text-xl shadow-inner">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-white">Konfirmasi & Ringkasan</h3>
                            <p class="text-xs text-slate-500 font-medium mt-1">Periksa kembali data anda sebelum melanjutkan.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        {{-- Vehicle --}}
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/10 space-y-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gold/10 text-gold flex items-center justify-center flex-shrink-0"><i class="fas fa-car-side"></i></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Unit Terpilih</p>
                                    <p class="text-sm font-bold text-white">{{ count($cars) }} Unit</p>
                                </div>
                            </div>
                            <div class="space-y-2 pt-2 border-t border-white/5">
                                @foreach($cars as $car)
                                <div class="flex justify-between items-center bg-slate-900/50 p-3 rounded-xl border border-white/5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-xs text-gold"><i class="fas fa-car"></i></div>
                                        <p class="text-xs font-bold text-white">{{ $car->car_name }}</p>
                                    </div>
                                    <span class="text-[9px] font-black text-slate-500 uppercase">{{ $car->transmission }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- Schedule --}}
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gold/10 text-gold flex items-center justify-center flex-shrink-0"><i class="fas fa-calendar-days"></i></div>
                            <div class="flex-1">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Jadwal Sewa</p>
                                <p class="text-sm font-bold text-white">
                                    {{ $pickup_date ? \Carbon\Carbon::parse($pickup_date)->format('d M Y') : '-' }}
                                    <span class="text-slate-500">→</span>
                                    {{ $return_date ? \Carbon\Carbon::parse($return_date)->format('d M Y') : '-' }}
                                </p>
                                <p class="text-[10px] text-gold font-bold mt-0.5">{{ $total_days }} Hari</p>
                            </div>
                        </div>
                        {{-- Renter --}}
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gold/10 text-gold flex items-center justify-center flex-shrink-0"><i class="fas fa-user-check"></i></div>
                            <div class="flex-1">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Penyewa</p>
                                <p class="text-sm font-bold text-white">{{ $name }}</p>
                                <p class="text-[10px] text-slate-500">{{ $phone }} · {{ $email }}</p>
                            </div>
                        </div>
                        {{-- Location & Type --}}
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/10 space-y-3">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gold/10 text-gold flex items-center justify-center flex-shrink-0"><i class="fas fa-map-location-dot"></i></div>
                                <div class="flex-1">
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Lokasi Antar & Jemput</p>
                                    <p class="text-xs font-bold text-white">Metode & Alamat</p>
                                </div>
                            </div>
                            <div class="space-y-2 pt-2 border-t border-white/5 text-[11px]">
                                <div class="flex justify-between items-center text-slate-400">
                                    <span>Pengantaran:</span>
                                    <span class="text-white font-bold">
                                        @if($delivery_type === 'none') Ambil Sendiri @elseif($delivery_type === 'standard') Diantar ke Lokasi @else Diantar ke Bandara @endif
                                    </span>
                                </div>
                                @if($delivery_type !== 'none')
                                <div class="text-[10px] text-slate-500 bg-slate-950/40 p-2 rounded-lg border border-white/5 leading-relaxed">
                                    <i class="fas fa-map-pin mr-1 text-gold"></i> {{ $pickup_location }}
                                </div>
                                @endif

                                <div class="flex justify-between items-center text-slate-400 pt-1">
                                    <span>Penjemputan:</span>
                                    <span class="text-white font-bold">
                                        @if($pickup_type === 'none') Kembalikan Sendiri @elseif($pickup_type === 'standard') Dijemput di Lokasi @else Dijemput di Bandara @endif
                                    </span>
                                </div>
                                @if($pickup_type !== 'none')
                                <div class="text-[10px] text-slate-500 bg-slate-950/40 p-2 rounded-lg border border-white/5 leading-relaxed">
                                    <i class="fas fa-map mr-1 text-gold"></i> {{ $return_location }}
                                </div>
                                @endif

                                @if($ojol_fee > 0)
                                <div class="flex justify-between items-center text-slate-400 pt-1">
                                    <span>Biaya Ojol:</span>
                                    <span class="text-gold font-bold">Rp {{ number_format($ojol_fee, 0, ',', '.') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        {{-- Driver --}}
                        <div class="p-5 rounded-2xl bg-white/5 border border-white/10 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-gold/10 text-gold flex items-center justify-center flex-shrink-0"><i class="fas fa-steering-wheel"></i></div>
                            <div class="flex-1">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Mode Berkendara</p>
                                <p class="text-sm font-bold text-white">{{ $with_driver ? '👨‍✈️ Dengan Driver' : '🔑 Lepas Kunci' }}</p>
                                @if(!$with_driver && $dest_type === 'luar_jabotabek')
                                <p class="text-[10px] text-slate-400 mt-1 leading-relaxed">
                                    Wilayah: <strong class="text-gold">
                                        {{ match($dest_region) {
                                            'jabar'   => 'Jawa Barat',
                                            'jateng'  => 'Jawa Tengah',
                                            'diy'     => 'D.I. Yogyakarta',
                                            'jatim'   => 'Jawa Timur & Madura',
                                            'banten'  => 'Banten',
                                            'bali'    => 'Bali',
                                            default   => 'Luar Jabotabek',
                                        } }} ({{ $dest_city }})
                                    </strong>
                                </p>
                                @endif
                            </div>
                            @if($with_driver)
                            <span class="text-xs font-black text-gold">+Rp {{ number_format($driver_fee, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="mt-8 pt-6 border-t border-white/5">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4">Metode Pembayaran</p>
                        <div class="flex items-center gap-6 text-slate-600">
                            <i class="fab fa-cc-visa text-3xl"></i>
                            <i class="fab fa-cc-mastercard text-3xl"></i>
                            <span class="px-3 py-1 rounded-lg bg-white/5 text-[9px] font-black uppercase tracking-widest">VA Transfer</span>
                            <span class="px-3 py-1 rounded-lg bg-white/5 text-[9px] font-black uppercase tracking-widest">QRIS</span>
                        </div>
                    </div>
                </div>
                @endif

            </div>

            {{-- Navigation Buttons --}}
            <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-6 pt-4">
                @if($step > 1)
                <button wire:click="previousStep" class="w-full sm:w-auto px-10 py-5 rounded-2xl bg-white/5 border border-white/10 text-slate-400 font-black text-[10px] uppercase tracking-widest hover:bg-white/10 hover:text-white transition-all flex items-center justify-center gap-3">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
                @else
                <a href="{{ route('cars.index') }}" class="w-full sm:w-auto px-10 py-5 rounded-2xl bg-white/5 border border-white/10 text-slate-400 font-black text-[10px] uppercase tracking-widest hover:bg-white/10 hover:text-white transition-all flex items-center justify-center gap-3">
                    <i class="fas fa-xmark"></i> Batal
                </a>
                @endif

                @if($step < 5)
                    <button type="button" wire:click.prevent="nextStep" wire:loading.attr="disabled" class="w-full sm:w-auto px-16 py-5 rounded-2xl bg-gold text-slate-950 font-black text-xs uppercase tracking-[0.2em] hover:scale-105 active:scale-95 transition-all shadow-xl shadow-gold/20 flex items-center justify-center gap-4 group disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="nextStep">Lanjutkan</span>
                        <span wire:loading wire:target="nextStep"><i class="fas fa-spinner fa-spin mr-2"></i></span>
                        <i class="fas fa-chevron-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    @else
                    <button wire:click="submit" class="w-full sm:w-auto px-20 py-6 rounded-2xl bg-gradient-to-br from-gold via-gold to-gold-dark text-slate-950 font-black text-sm uppercase tracking-[0.2em] hover:scale-105 active:scale-95 transition-all shadow-2xl shadow-gold/30 flex items-center justify-center gap-4 group">
                        <span wire:loading.remove wire:target="submit">Konfirmasi & Bayar</span>
                        <span wire:loading wire:target="submit"><i class="fas fa-spinner fa-spin mr-2"></i> Proses...</span>
                        <i class="fas fa-paper-plane text-xs group-hover:-translate-y-1 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    @endif
            </div>
        </div>

        {{-- ── RIGHT: Sidebar Summary ── --}}
        <aside class="w-full lg:w-[40%] xl:w-[35%] lg:sticky lg:top-28">
            <div class="bg-white/5 backdrop-blur-3xl rounded-[2rem] border border-white/10 shadow-2xl overflow-hidden" data-aos="fade-left">
                {{-- Car Thumbnail --}}
                <div class="relative aspect-[16/10] bg-slate-900">
                    @php $firstCar = $cars->first(); @endphp
                    @if($firstCar && $firstCar->thumbnail)
                    <img src="{{ Storage::url($firstCar->thumbnail) }}" alt="{{ $firstCar->car_name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-slate-800">
                        <i class="fas fa-car-side text-6xl"></i>
                    </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent"></div>
                    <div class="absolute bottom-6 left-8">
                        <p class="text-gold text-[9px] font-black uppercase tracking-[0.3em] mb-1">
                            @if(count($cars) > 1) {{ count($cars) }} UNIT @else {{ $firstCar->brand->name ?? 'Luxury Fleet' }} @endif
                        </p>
                        <h4 class="text-xl font-black text-white tracking-tight">
                            @if(count($cars) > 1) Pilihan Ganda @else {{ $firstCar->car_name }} @endif
                        </h4>
                    </div>
                    @if(count($cars) == 1)
                    <div class="absolute top-6 right-6 px-3 py-1.5 rounded-xl bg-slate-950/80 backdrop-blur-md border border-white/10 text-white text-[9px] font-black uppercase tracking-widest">
                        {{ $firstCar->transmission }}
                    </div>
                    @endif
                </div>

                <div class="p-8 space-y-8">
                    {{-- Booking Details Summary --}}
                    <div class="space-y-4">
                        <h5 class="text-[10px] font-black text-slate-500 uppercase tracking-widest border-b border-white/5 pb-2">Ringkasan Sewa</h5>

                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-400">Durasi</span>
                            <span class="text-white font-black">{{ $total_days }} Hari</span>
                        </div>

                        @if($pickup_date && $return_date)
                        <div class="flex justify-between items-start text-xs">
                            <span class="text-slate-400">Period</span>
                            <span class="text-white font-bold text-right text-[10px]">
                                {{ \Carbon\Carbon::parse($pickup_date)->format('d M') }} — {{ \Carbon\Carbon::parse($return_date)->format('d M Y') }}
                            </span>
                        </div>
                        @endif

                        <div class="flex justify-between items-center text-xs pt-2">
                            <span class="text-slate-400">Layanan</span>
                            <span class="px-2 py-1 rounded-lg bg-gold/10 text-gold font-black text-[9px] uppercase">{{ $with_driver ? 'Plus Driver' : 'Self Drive' }}</span>
                        </div>

                        <div class="flex justify-between items-center text-xs pt-2">
                            <span class="text-slate-400">Kategori</span>
                            <span class="px-2 py-1 rounded-lg bg-white/10 text-white font-black text-[9px] uppercase">{{ $rental_category == 'pribadi' ? 'Pribadi' : 'Perusahaan' }}</span>
                        </div>
                    </div>

                    {{-- Promo --}}
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Kode Promo</label>
                        <div class="flex gap-2">
                            <input type="text" wire:model="promo_code" class="flex-1 bg-slate-950/50 border border-white/10 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-white focus:border-gold outline-none placeholder:text-slate-700" placeholder="KODE PROMO">
                            <button wire:click="applyPromo" class="px-5 bg-white text-slate-950 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gold transition-colors">Cek</button>
                        </div>
                        @if($promo_message) <p class="text-emerald-400 text-[9px] font-black mt-1 uppercase tracking-widest flex items-center gap-1"><i class="fas fa-check-circle"></i> {{ $promo_message }}</p> @endif
                        @if($promo_error) <p class="text-red-400 text-[9px] font-black mt-1 uppercase tracking-widest flex items-center gap-1"><i class="fas fa-triangle-exclamation"></i> {{ $promo_error }}</p> @endif
                    </div>

                    {{-- Price Breakdown --}}
                    <div class="pt-6 border-t border-white/5 space-y-4">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Harga Sewa</span>
                            <div class="text-right">
                                <span class="text-white font-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                @if($dest_multiplier > 1.0)
                                <span class="block text-[9px] text-gold font-bold mt-0.5">Luar Kota ({{ $dest_multiplier }}x)</span>
                                @endif
                            </div>
                        </div>

                        @if($with_driver)
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Fee Driver</span>
                            <span class="text-gold font-bold">Rp {{ number_format($driver_fee, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Biaya Operasional</span>
                            <span class="text-white font-bold">Rp {{ number_format($operational_fee, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Biaya Admin</span>
                            <span class="text-white font-bold">Rp {{ number_format($admin_fee, 0, ',', '.') }}</span>
                        </div>

                        @if($delivery_fee > 0)
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Biaya Pengantaran</span>
                            <span class="text-white font-bold">Rp {{ number_format($delivery_fee, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        @if($pickup_fee > 0)
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Biaya Penjemputan</span>
                            <span class="text-white font-bold">Rp {{ number_format($pickup_fee, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        @if($ojol_fee > 0)
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Biaya Ojol</span>
                            <span class="text-gold font-bold">Rp {{ number_format($ojol_fee, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Tax (12%)</span>
                            <span class="text-red-400 font-bold">+ Rp {{ number_format($tax_amount, 0, ',', '.') }}</span>
                        </div>

                        @if($promo_discount > 0)
                        <div class="flex justify-between items-center text-xs text-emerald-400 font-bold">
                            <span>Diskon Promo</span>
                            <span>- Rp {{ number_format($promo_discount, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        <div class="h-px bg-white/5 my-2"></div>

                        <div class="flex justify-between items-end">
                            <div class="text-left">
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Pembayaran</p>
                                <p class="text-2xl font-black text-white tracking-tighter">Rp {{ number_format($grand_total, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 rounded bg-emerald-500/10 text-emerald-400 text-[8px] font-black uppercase tracking-widest border border-emerald-500/20">Tax Inc.</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Security Badge --}}
                <div class="bg-emerald-500/5 p-6 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-lg">
                        <i class="fas fa-shield-check"></i>
                    </div>
                    <p class="text-[9px] text-slate-400 font-medium leading-relaxed">Transaksi ini aman dengan enkripsi SSL 256-bit.</p>
                </div>
            </div>

            {{-- Support Info --}}
            <div class="mt-6 p-6 rounded-3xl bg-white/5 border border-white/5 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center text-lg">
                    <i class="fas fa-headset"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-white uppercase tracking-widest">Butuh Bantuan?</p>
                    <p class="text-[9px] text-slate-500">Hubungi 0812-xxxx-xxxx</p>
                </div>
            </div>
            {{-- SUCCESS MODAL --}}
            @if($is_finished)
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                {{-- Backdrop --}}
                <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-md animate-fade-in"></div>

                {{-- Modal Content --}}
                <div class="relative w-full max-w-md bg-white/5 border border-white/10 backdrop-blur-3xl rounded-[2.5rem] p-10 text-center shadow-2xl animate-scale-up border-b-4 border-b-gold">
                    {{-- Success Icon --}}
                    <div class="w-24 h-24 rounded-full bg-gold/10 flex items-center justify-center mx-auto mb-8 relative">
                        <div class="absolute inset-0 rounded-full bg-gold/20 animate-ping"></div>
                        <i class="fas fa-check-circle text-5xl text-gold relative z-10"></i>
                    </div>

                    <h2 class="text-3xl font-black text-white mb-3">Booking Berhasil!</h2>
                    <p class="text-slate-400 text-xs font-medium mb-10 leading-relaxed">
                        Invoice <span class="text-white font-bold">#{{ $final_booking_code }}</span> berhasil dibuat. Pilih langkah selanjutnya.
                    </p>

                    <div class="flex flex-col gap-4">
                        {{-- Lanjut Button --}}
                        @if(Auth::check())
                        <a href="{{ route('invoice', $final_booking_code) }}" class="w-full py-5 rounded-2xl bg-gold text-slate-950 font-black text-xs uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-gold/20 flex items-center justify-center gap-3">
                            Lanjut Pembayaran <i class="fas fa-arrow-right"></i>
                        </a>
                        @else
                        <a href="{{ $wa_url }}" target="_blank" class="w-full py-5 rounded-2xl bg-emerald-500 text-white font-black text-xs uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-3">
                            Lanjut via WhatsApp <i class="fab fa-whatsapp text-lg"></i>
                        </a>
                        @endif

                        {{-- Home Button --}}
                        <a href="{{ url('/') }}" class="w-full py-5 rounded-2xl bg-white/5 border border-white/10 text-slate-400 font-black text-[10px] uppercase tracking-widest hover:bg-white/10 hover:text-white transition-all">
                            Kembali ke Beranda
                        </a>
                    </div>

                    <p class="mt-8 text-[9px] text-slate-600 font-bold uppercase tracking-[0.2em]">Siliwangi Rental — Executive Fleet</p>
                </div>
            </div>
            @endif
        </aside>
        @push('scripts')
        <script>
            document.addEventListener('livewire:navigated', () => {
                Livewire.on('orderFinalized', () => {
                    confetti({
                        particleCount: 200,
                        spread: 100,
                        origin: {
                            y: 0.6
                        },
                        colors: ['#D4AF37', '#ffffff', '#10B981'],
                        ticks: 300,
                        gravity: 1.2
                    });
                });
            });
        </script>
        @endpush
    </div>
</div>