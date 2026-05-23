@extends('layouts.admin')

@section('title', 'Fleet Intelligence – Siliwangi Admin')

@section('styles')
<style>
    :root {
        --card-bg-premium: rgba(255, 255, 255, 0.8);
        --card-border-premium: rgba(255, 255, 255, 0.4);
    }
    
    [data-theme="dark"] {
        --card-bg-premium: rgba(15, 23, 42, 0.6);
        --card-border-premium: rgba(255, 255, 255, 0.05);
    }

    .fleet-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 32px;
        margin-top: 32px;
    }

    .car-card-premium {
        background: var(--card-bg-premium);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--card-border-premium);
        border-radius: var(--radius-xl);
        overflow: hidden;
        transition: var(--transition);
        position: relative;
        display: flex;
        flex-direction: column;
        box-shadow: var(--card-shadow);
    }
    
    .car-card-premium:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 30px 60px -12px rgba(0,0,0,0.15);
        border-color: var(--secondary);
    }

    .car-media {
        position: relative;
        height: 240px;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.05), rgba(15, 23, 42, 0.05));
    }
    
    .car-media img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 20px;
        transition: transform 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    
    .car-card-premium:hover .car-media img {
        transform: scale(1.15) rotate(-2deg);
    }

    .car-status-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        backdrop-filter: blur(12px);
        z-index: 10;
        border: 1px solid rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .car-status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        box-shadow: 0 0 10px currentColor;
    }

    .status-available { background: rgba(16, 185, 129, 0.15); color: #10B981; }
    .status-rented { background: rgba(139, 92, 246, 0.15); color: #8B5CF6; }
    .status-maintenance { background: rgba(245, 158, 11, 0.15); color: #F59E0B; }

    .car-plate-tag {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(15, 23, 42, 0.8);
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        font-weight: 700;
        z-index: 10;
        border: 1px solid rgba(255,255,255,0.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .car-content {
        padding: 32px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .car-brand-info {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }
    
    .brand-logo-mini {
        width: 20px;
        height: 20px;
        object-fit: contain;
        opacity: 0.8;
    }

    .car-category {
        font-size: 11px;
        font-weight: 800;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .car-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -0.5px;
        margin-bottom: 20px;
    }

    .car-specs-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 28px;
        padding: 16px;
        background: rgba(var(--secondary-rgb), 0.03);
        border-radius: var(--radius-lg);
        border: 1px solid var(--card-border);
    }
    
    .spec-pill {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        text-align: center;
    }
    
    .spec-pill i {
        font-size: 14px;
        color: var(--secondary);
    }
    
    .spec-label {
        font-size: 10px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
    }

    .car-footer {
        margin-top: auto;
        padding-top: 24px;
        border-top: 1px dashed var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .price-container {
        display: flex;
        flex-direction: column;
    }
    
    .price-amount {
        font-size: 24px;
        font-weight: 900;
        color: var(--text-main);
        line-height: 1;
    }
    
    .price-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-muted);
        margin-top: 4px;
    }

    .action-group {
        display: flex;
        gap: 10px;
    }
    
    .action-btn {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        border: 1px solid var(--card-border);
        background: var(--card-bg);
    }
    
    .action-btn:hover {
        transform: scale(1.1);
        background: var(--secondary);
        color: var(--primary);
        border-color: var(--secondary);
    }
    
    .btn-delete:hover {
        background: var(--danger);
        color: white;
        border-color: var(--danger);
    }

    /* KPI Enhancements */
    .premium-kpi-card {
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    
    .premium-kpi-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, var(--secondary-glow) 0%, transparent 70%);
        opacity: 0.2;
        z-index: -1;
    }

    .fleet-filters {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-xl);
        padding: 16px 24px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-top: 32px;
        box-shadow: var(--card-shadow);
    }
    
    .filter-search {
        position: relative;
        flex: 1;
        min-width: 300px;
    }
    
    .filter-search input {
        width: 100%;
        background: var(--bg-color);
        border: 1px solid var(--card-border);
        padding: 12px 16px 12px 48px;
        border-radius: 16px;
        font-size: 13px;
        outline: none;
        transition: var(--transition);
    }
    
    .filter-search input:focus {
        border-color: var(--secondary);
        box-shadow: 0 0 0 4px var(--secondary-glow);
    }
    
    .filter-search i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }

    .view-toggle {
        display: flex;
        background: var(--bg-color);
        padding: 4px;
        border-radius: 12px;
        border: 1px solid var(--card-border);
    }
    
    .toggle-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        transition: var(--transition);
    }
    
    .toggle-btn.active {
        background: var(--card-bg);
        color: var(--secondary);
        box-shadow: var(--card-shadow);
    }

    /* Bulk Actions Bar */
    .bulk-actions-bar {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 16px 32px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        gap: 24px;
        z-index: 1000;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        opacity: 0;
    }
    
    .bulk-actions-bar.active {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }

    .selection-checkbox {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 20;
        width: 24px;
        height: 24px;
        border-radius: 8px;
        cursor: pointer;
        opacity: 0;
        transition: var(--transition);
    }

    .car-card-premium:hover .selection-checkbox,
    .selection-checkbox:checked {
        opacity: 1;
    }

    .bulk-count {
        font-size: 14px;
        font-weight: 800;
        color: var(--secondary);
        padding-right: 24px;
        border-right: 1px solid rgba(255,255,255,0.1);
    }
</style>
@endsection

@section('content')

<div class="page-header" data-aos="fade-down">
    <div>
        <h1 class="page-title">Intelijen Armada</h1>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">Akar Sistem</a>
            <i class="fas fa-chevron-right" style="font-size: 8px; opacity: 0.5;"></i>
            <span style="color: var(--secondary); font-weight: 700;">Inti Inventaris</span>
        </div>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.cars.create') }}" class="btn btn-gold px-10 py-4 rounded-2xl text-xs font-black uppercase tracking-widest shadow-2xl shadow-gold/30 transition-all hover:-translate-y-1 active:translate-y-0">
            <i class="fas fa-plus-circle mr-2"></i> Daftarkan Aset Baru
        </a>
    </div>
</div>

<!-- Fleet KPI Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="card premium-kpi-card" data-aos="fade-up" data-aos-delay="0">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl" style="background: var(--info-bg); color: var(--info-text);">
                <i class="fas fa-warehouse-full"></i>
            </div>
            <div>
                <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Armada</div>
                <div class="text-3xl font-black">{{ $stats['total_cars'] }} <span class="text-xs font-bold text-slate-500 uppercase">Unit</span></div>
            </div>
        </div>
    </div>
    
    <div class="card premium-kpi-card" data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl" style="background: var(--success-bg); color: var(--success-text);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Siap Pasar</div>
                <div class="text-3xl font-black">{{ $stats['available_cars'] }} <span class="text-xs font-bold text-slate-500 uppercase">Aktif</span></div>
            </div>
        </div>
    </div>
    
    <div class="card premium-kpi-card" data-aos="fade-up" data-aos-delay="200">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl" style="background: var(--secondary-glow); color: var(--secondary);">
                <i class="fas fa-key-skeleton"></i>
            </div>
            <div>
                <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Pemanfaatan Aktif</div>
                <div class="text-3xl font-black">{{ $stats['rented_cars'] }} <span class="text-xs font-bold text-slate-500 uppercase">Dikerahkan</span></div>
            </div>
        </div>
    </div>
    
    <div class="card premium-kpi-card" data-aos="fade-up" data-aos-delay="300">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl" style="background: var(--danger-bg); color: var(--danger-text);">
                <i class="fas fa-screwdriver-wrench"></i>
            </div>
            <div>
                <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Mode Pemeliharaan</div>
                <div class="text-3xl font-black">{{ $stats['maintenance_cars'] }} <span class="text-xs font-bold text-slate-500 uppercase">Dalam Perbaikan</span></div>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Filters Bar -->
<form action="{{ route('admin.cars.index') }}" method="GET" class="fleet-filters" data-aos="fade-up">
    <div class="filter-search">
        <i class="fas fa-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama, merek, atau nomor plat..." onchange="this.form.submit()">
    </div>
    
    <div class="flex gap-4 items-center">
        <select name="category" onchange="this.form.submit()" class="btn-glass px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest outline-none border-white/5 bg-slate-100 dark:bg-white/5">
            <option value="">Kategori: Semua</option>
            <option value="pribadi" {{ request('category') == 'pribadi' ? 'selected' : '' }}>Pribadi</option>
            <option value="perusahaan" {{ request('category') == 'perusahaan' ? 'selected' : '' }}>Perusahaan</option>
            <option value="both" {{ request('category') == 'both' ? 'selected' : '' }}>Keduanya</option>
        </select>
        
        <select name="status" onchange="this.form.submit()" class="btn-glass px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest outline-none border-white/5 bg-slate-100 dark:bg-white/5">
            <option value="">Status: Semua</option>
            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia (Siap)</option>
            <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Disewa (Dikerahkan)</option>
            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Pemeliharaan</option>
        </select>
        
        <div class="view-toggle">
            <button type="button" class="toggle-btn active"><i class="fas fa-grid-2"></i></button>
            <button type="button" class="toggle-btn"><i class="fas fa-list-ul"></i></button>
        </div>
    </div>
</form>

<!-- Fleet Grid Intelligence -->
<form id="bulkForm" action="{{ route('admin.cars.bulk-update') }}" method="POST">
    @csrf
    <div class="fleet-grid">
        @foreach($cars as $car)
        @php
            $statusData = [
                'available' => ['status-available', 'Siap Pasar'],
                'rented' => ['status-rented', 'Dikerahkan'],
                'maintenance' => ['status-maintenance', 'Dalam Pemeliharaan']
            ];
            $currentStatus = $car->is_available ? 'available' : ($car->status == 'maintenance' ? 'maintenance' : 'rented');
            $display = $statusData[$currentStatus] ?? ['bg-slate-500', 'Unknown'];
        @endphp
        
        <div class="car-card-premium" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
            <input type="checkbox" name="ids[]" value="{{ $car->id }}" class="selection-checkbox car-select">
            <div class="car-media">
            <div class="car-status-badge {{ $display[0] }}">{{ $display[1] }}</div>
            <div class="car-plate-tag">{{ $car->plate_number }}</div>
            
            @if($car->thumbnail)
                <img src="{{ Storage::url($car->thumbnail) }}" alt="{{ $car->car_name }}">
            @else
                <div class="w-full h-full flex items-center justify-center opacity-10">
                    <i class="fas fa-car text-8xl"></i>
                </div>
            @endif
            
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 via-transparent to-transparent"></div>
        </div>
        
        <div class="car-content">
            <div class="car-brand-info">
                @if($car->brand && $car->brand->logo)
                    <img src="{{ Storage::url($car->brand->logo) }}" class="brand-logo-mini" alt="{{ $car->brand->name }}">
                @endif
                <span class="car-category">{{ $car->brand->name ?? 'Premium' }} • {{ $car->type->name ?? 'Vehicle' }}</span>
                <span class="ml-auto px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-tighter {{ $car->category == 'Perusahaan' ? 'bg-blue-100 text-blue-600' : ($car->category == 'Pribadi' ? 'bg-purple-100 text-purple-600' : 'bg-slate-100 text-slate-600') }}">
                    {{ $car->category }}
                </span>
            </div>
            
            <h3 class="car-title">{{ $car->car_name }}</h3>
            
            <div class="car-specs-row">
                <div class="spec-pill">
                    <i class="fas fa-users"></i>
                    <span class="spec-label">{{ $car->seat }} Kursi</span>
                </div>
                <div class="spec-pill">
                    <i class="fas fa-gears"></i>
                    <span class="spec-label">{{ $car->transmission }}</span>
                </div>
                <div class="spec-pill">
                    <i class="fas fa-bolt"></i>
                    <span class="spec-label">{{ $car->fuel_type }}</span>
                </div>
            </div>
            
            <div class="flex items-center gap-3 mb-8 px-2">
                <div class="w-2 h-2 rounded-full bg-gold animate-pulse"></div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Berlokasi di: {{ $car->branch->name ?? 'Pusat Utama' }}</span>
            </div>
            
            <div class="car-footer">
                <div class="price-container">
                    <div class="price-amount">Rp {{ number_format($car->daily_price, 0, ',', '.') }}</div>
                    <div class="price-label">TARIF OPERASIONAL HARIAN</div>
                </div>
                
                <div class="action-group">
                    <a href="{{ route('admin.cars.edit', $car->id) }}" class="action-btn" title="Ubah Aset">
                        <i class="fas fa-pen-nib text-xs"></i>
                    </a>
                    <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" onsubmit="return confirm('Arsipkan aset ini dari armada?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-delete" title="Arsipkan Aset">
                            <i class="fas fa-box-archive text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    </div>

    <!-- Floating Bulk Actions -->
    <div class="bulk-actions-bar" id="bulkBar">
        <div class="bulk-count">
            <span id="selectedCount">0</span> Unit Terpilih
        </div>
        
        <div class="flex gap-3">
            <select name="category" class="form-select bg-white/10 border-0 text-white text-xs rounded-xl px-4 py-2 outline-none" style="background-color: rgba(255,255,255,0.1);">
                <option value="" class="text-slate-900">Ubah Kategori...</option>
                <option value="pribadi" class="text-slate-900">Set: Pribadi</option>
                <option value="perusahaan" class="text-slate-900">Set: Perusahaan</option>
                <option value="both" class="text-slate-900">Set: Keduanya</option>
            </select>
            
            <select name="status" class="form-select bg-white/10 border-0 text-white text-xs rounded-xl px-4 py-2 outline-none" style="background-color: rgba(255,255,255,0.1);">
                <option value="" class="text-slate-900">Ubah Status...</option>
                <option value="available" class="text-slate-900">Set: Tersedia</option>
                <option value="rented" class="text-slate-900">Set: Disewa</option>
                <option value="maintenance" class="text-slate-900">Set: Perbaikan</option>
            </select>
            
            <button type="submit" class="btn btn-gold px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">
                Terapkan Perubahan
            </button>
            
            <button type="button" onclick="cancelSelection()" class="btn btn-outline text-white border-white/20 hover:bg-white/10 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest">
                Batal
            </button>
        </div>
    </div>
</form>

<div class="mt-16 mb-24">
    {{ $cars->links() }}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.car-select');
        const bulkBar = document.getElementById('bulkBar');
        const countSpan = document.getElementById('selectedCount');

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkBar);
        });

        function updateBulkBar() {
            const selected = document.querySelectorAll('.car-select:checked');
            countSpan.innerText = selected.length;
            
            if (selected.length > 0) {
                bulkBar.classList.add('active');
            } else {
                bulkBar.classList.remove('active');
            }
        }
    });

    function cancelSelection() {
        document.querySelectorAll('.car-select').forEach(cb => cb.checked = false);
        document.getElementById('bulkBar').classList.remove('active');
    }
</script>

@endsection