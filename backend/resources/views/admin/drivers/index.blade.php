@extends('layouts.admin')

@section('title', 'Manajemen Supir - Siliwangi Admin')

@section('styles')
<style>
    /* Summary Cards */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    .summary-card {
        padding: 20px;
        border-radius: var(--radius-lg);
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s;
    }
    .summary-card:hover {
        transform: translateY(-2px);
    }
    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .summary-info h4 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 2px;
    }
    .summary-info p {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Driver Grid */
    .driver-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
    }
    .driver-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .driver-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow);
        border-color: var(--secondary);
    }
    .driver-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
    }
    .driver-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--bg-color);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .driver-name {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .driver-rating {
        color: #FBBF24;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .rating-text {
        color: var(--text-muted);
        font-weight: 600;
    }
    .driver-status {
        position: absolute;
        top: 20px;
        right: 20px;
    }
    
    .driver-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 16px;
        background: var(--bg-color);
        padding: 12px;
        border-radius: var(--radius-sm);
    }
    .d-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .d-stat-val { font-weight: 700; font-size: 14px; }
    .d-stat-lbl { font-size: 11px; color: var(--text-muted); text-transform: uppercase; }
    
    .driver-assignment {
        font-size: 13px;
        padding-top: 16px;
        border-top: 1px dashed var(--card-border);
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-muted);
    }
    .driver-assignment.active { color: var(--text-main); font-weight: 600; }
    
    .driver-actions {
        display: flex;
        gap: 8px;
        margin-top: 16px;
    }
    .driver-actions .btn { flex: 1; padding: 8px; font-size: 12px; }

    /* Modal Styles */
    .modal-overlay {
        display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 100;
        align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s;
    }
    .modal-overlay.show { display: flex; opacity: 1; }
    .modal-content {
        background: var(--card-bg); border: 1px solid var(--card-border);
        border-radius: var(--radius-lg); width: 100%; max-width: 600px;
        transform: translateY(20px); transition: transform 0.3s;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    .modal-overlay.show .modal-content { transform: translateY(0); }
    .modal-header {
        padding: 20px 24px; border-bottom: 1px solid var(--card-border);
        display: flex; justify-content: space-between; align-items: center;
        background: var(--bg-color); border-top-left-radius: var(--radius-lg); border-top-right-radius: var(--radius-lg);
    }
    .modal-title { font-size: 1.2rem; font-weight: 700; }
    .close-modal { font-size: 20px; color: var(--text-muted); cursor: pointer; }
    .close-modal:hover { color: var(--danger); }
    .modal-body { padding: 24px; }
    
    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--text-muted); }
    .form-control {
        width: 100%; padding: 10px 14px; background: var(--bg-color);
        border: 1px solid var(--card-border); border-radius: 6px;
        color: var(--text-main); font-size: 14px;
    }
    .form-control:focus { outline: none; border-color: var(--secondary); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1); }
    
    .modal-footer {
        padding: 16px 24px; border-top: 1px solid var(--card-border);
        display: flex; justify-content: flex-end; gap: 12px;
        background: var(--bg-color); border-bottom-left-radius: var(--radius-lg); border-bottom-right-radius: var(--radius-lg);
    }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Supir</h1>
        <div class="breadcrumb">
            <span>Siliwangi Admin</span>
            <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
            <span style="color: var(--secondary)">Drivers</span>
        </div>
    </div>
    <div class="header-actions">
        <button class="btn btn-outline" style="margin-right: 8px;">
            <i class="fas fa-filter"></i> Filter
        </button>
        <button class="btn btn-gold" onclick="openModal('addDriverModal')">
            <i class="fas fa-plus"></i> Tambah Supir
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-icon" style="background: var(--info-bg); color: var(--info-text);">
            <i class="fas fa-users"></i>
        </div>
        <div class="summary-info">
            <h4>{{ $stats['total_drivers'] }}</h4>
            <p>Total Supir</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background: var(--success-bg); color: var(--success-text);">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="summary-info">
            <h4>{{ $stats['available_drivers'] }}</h4>
            <p>Tersedia (Standby)</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background: var(--warning-bg); color: var(--warning-text);">
            <i class="fas fa-route"></i>
        </div>
        <div class="summary-info">
            <h4>{{ $stats['on_duty_drivers'] }}</h4>
            <p>Sedang Bertugas</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-icon" style="background: var(--danger-bg); color: var(--danger-text);">
            <i class="fas fa-bed"></i>
        </div>
        <div class="summary-info">
            <h4>{{ $stats['off_drivers'] }}</h4>
            <p>Cuti / Off</p>
        </div>
    </div>
</div>

<div style="margin-bottom: 24px;">
    <div class="search-box" style="display: inline-block; width: 300px;">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Cari nama supir atau lisensi...">
    </div>
</div>

<!-- Driver Grid -->
<div class="driver-grid">
    @foreach($drivers as $driver)
    <div class="driver-card" @if($driver->status == 'inactive') style="opacity: 0.8;" @endif>
        <div class="driver-status">
            @if($driver->is_available)
                <span class="badge badge-success">Standby</span>
            @elseif($driver->status == 'active')
                <span class="badge badge-warning">On Duty</span>
            @else
                <span class="badge badge-danger">Off</span>
            @endif
        </div>
        <div class="driver-header">
            @if($driver->photo)
                <img src="{{ Storage::url($driver->photo) }}" alt="{{ $driver->name }}" class="driver-avatar">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->name) }}&background=D4AF37&color=fff" alt="{{ $driver->name }}" class="driver-avatar">
            @endif
            <div>
                <div class="driver-name">{{ $driver->name }}</div>
                <div class="driver-rating">
                    @php
                        $fullStars = floor($driver->rating ?? 5);
                        $halfStar = ($driver->rating ?? 5) - $fullStars >= 0.5;
                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                    @endphp
                    @for($i=0; $i<$fullStars; $i++) <i class="fas fa-star"></i> @endfor
                    @if($halfStar) <i class="fas fa-star-half-alt"></i> @endif
                    @for($i=0; $i<$emptyStars; $i++) <i class="far fa-star"></i> @endfor
                    <span class="rating-text">{{ number_format($driver->rating ?? 5, 1) }}</span>
                </div>
            </div>
        </div>
        <div class="driver-stats">
            <div class="d-stat">
                <span class="d-stat-val">{{ $driver->license_number }}</span>
                <span class="d-stat-lbl">Lisensi</span>
            </div>
            <div class="d-stat">
                <span class="d-stat-val">{{ $driver->total_trips ?? 0 }}</span>
                <span class="d-stat-lbl">Total Trip</span>
            </div>
        </div>
        <div class="driver-assignment @if(!$driver->is_available) active @endif">
            @if($driver->is_available)
                <i class="fas fa-map-marker-alt"></i> {{ $driver->branch->name ?? 'Pool Pusat' }}
            @else
                <i class="fas fa-car"></i> Sedang Bertugas
            @endif
        </div>
        <div class="driver-actions">
            <button class="btn btn-outline" onclick="viewDriverDetail({{ $driver->id }})"><i class="fas fa-eye"></i> Detail</button>
            @if($driver->is_available)
                <button class="btn btn-gold"><i class="fas fa-calendar-alt"></i> Assign</button>
            @else
                <button class="btn btn-outline" style="border-color: var(--warning); color: var(--warning);"><i class="fas fa-location-arrow"></i> Lacak</button>
            @endif
        </div>
    </div>
    @endforeach
</div>

<div style="margin-top: 30px;">
    {{ $drivers->links() }}
</div>

<!-- ADD / EDIT DRIVER MODAL -->
<div class="modal-overlay" id="addDriverModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Tambah Supir Baru</h2>
            <i class="fas fa-times close-modal" onclick="closeModal('addDriverModal')"></i>
        </div>
        <div class="modal-body">
            <form id="driverForm">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Foto Profil</label>
                        <div style="border: 2px dashed var(--card-border); padding: 20px; text-align: center; border-radius: var(--radius-md); cursor: pointer;">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 24px; color: var(--text-muted); margin-bottom: 8px;"></i>
                            <div style="font-size: 13px; color: var(--text-muted);">Klik atau drag gambar ke sini</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" placeholder="Nama sesuai KTP">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon / WA</label>
                        <input type="text" class="form-control" placeholder="08...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipe Lisensi (SIM)</label>
                        <select class="form-control">
                            <option>SIM A</option>
                            <option>SIM A Umum</option>
                            <option>SIM B1</option>
                            <option>SIM B1 Umum</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor Lisensi</label>
                        <input type="text" class="form-control" placeholder="Nomor SIM">
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" rows="3" placeholder="Alamat domisili"></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal('addDriverModal')">Batal</button>
            <button class="btn btn-gold" onclick="saveDriver()">Simpan Data</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        document.body.style.overflow = '';
    }

    document.getElementById('addDriverModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal('addDriverModal');
        }
    });

    function saveDriver() {
        closeModal('addDriverModal');
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data supir baru telah ditambahkan.',
            icon: 'success',
            background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1E293B' : '#FFFFFF',
            color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#F3F4F6' : '#1F2937'
        });
    }

    function viewDriverDetail() {
        // Placeholder for driver detail logic
        Swal.fire({
            title: 'Detail Supir',
            text: 'Fitur drawer detail supir (seperti pada halaman Customer) akan dimuat.',
            icon: 'info',
            background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1E293B' : '#FFFFFF',
            color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#F3F4F6' : '#1F2937'
        });
    }
</script>
@endsection
