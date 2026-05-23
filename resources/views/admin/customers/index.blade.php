@extends('layouts.admin')

@section('title', 'Manajemen Customer - Siliwangi Admin')

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
        justify-content: space-between;
        transition: transform 0.2s;
    }
    .summary-card:hover {
        transform: translateY(-2px);
    }
    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .summary-info h4 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 2px;
        font-family: 'Poppins', sans-serif;
    }
    .summary-info p {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Table */
    .table-container { overflow-x: auto; }
    .admin-table { width: 100%; border-collapse: collapse; text-align: left; }
    .admin-table th {
        background: var(--bg-color); padding: 12px 16px; font-size: 12px;
        text-transform: uppercase; letter-spacing: 1px; font-weight: 600;
        color: var(--text-muted); border-bottom: 1px solid var(--card-border);
    }
    .admin-table td { padding: 16px; border-bottom: 1px solid var(--card-border); font-size: 13px; vertical-align: middle; }
    .admin-table tbody tr { transition: background 0.2s; }
    .admin-table tbody tr:hover { background: var(--bg-color); cursor: pointer; }
    
    .customer-cell { display: flex; align-items: center; gap: 12px; }
    .customer-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--card-border); }

    /* Side Drawer for Customer Detail */
    .drawer-overlay {
        display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 100;
        opacity: 0; transition: opacity 0.3s;
    }
    .drawer-overlay.show { display: block; opacity: 1; }
    
    .drawer {
        position: fixed; top: 0; right: -500px; bottom: 0;
        width: 100%; max-width: 500px;
        background: var(--card-bg);
        box-shadow: -10px 0 30px rgba(0,0,0,0.1);
        z-index: 101; transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex; flex-direction: column;
    }
    .drawer-overlay.show + .drawer { right: 0; }
    
    .drawer-header {
        padding: 24px; border-bottom: 1px solid var(--card-border);
        display: flex; justify-content: space-between; align-items: flex-start;
        background: var(--bg-color);
    }
    .close-drawer { font-size: 20px; color: var(--text-muted); }
    .close-drawer:hover { color: var(--danger); }
    
    .drawer-body {
        padding: 24px; flex-grow: 1; overflow-y: auto;
    }
    
    /* Profile Section inside Drawer */
    .profile-header {
        display: flex; flex-direction: column; align-items: center; text-align: center;
        margin-bottom: 24px;
    }
    .profile-avatar {
        width: 80px; height: 80px; border-radius: 50%; object-fit: cover;
        border: 3px solid var(--secondary); margin-bottom: 12px;
    }
    .profile-name { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
    
    .profile-stats {
        display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px;
    }
    .stat-box {
        background: var(--bg-color); border: 1px solid var(--card-border);
        padding: 16px; border-radius: var(--radius-md); text-align: center;
    }
    .stat-box .val { font-size: 20px; font-weight: 700; color: var(--secondary); font-family: 'Poppins', sans-serif;}
    .stat-box .lbl { font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 600; margin-top: 4px; }
    
    .info-group { margin-bottom: 24px; }
    .info-group h4 { font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; border-bottom: 1px solid var(--card-border); padding-bottom: 8px; }
    .info-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; }
    .info-row .lbl { color: var(--text-muted); }
    .info-row .val { font-weight: 600; text-align: right; }
    
    .doc-preview {
        width: 100%; height: 140px; object-fit: cover; border-radius: var(--radius-sm);
        border: 1px solid var(--card-border); margin-top: 8px; cursor: pointer; transition: 0.2s;
    }
    .doc-preview:hover { opacity: 0.8; border-color: var(--secondary); }

    /* Action Dropdown */
    .action-dropdown { position: relative; display: inline-block; }
    .action-btn { padding: 6px; color: var(--text-muted); border-radius: 4px; transition: all 0.2s; }
    .action-btn:hover { background: var(--bg-color); color: var(--text-main); }
    .dropdown-content {
        display: none; position: absolute; right: 0; top: 100%;
        background-color: var(--card-bg); min-width: 160px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15); border: 1px solid var(--card-border);
        border-radius: var(--radius-sm); z-index: 10; overflow: hidden;
    }
    .action-dropdown.active .dropdown-content { display: block; }
    [data-theme="dark"] .dropdown-content { box-shadow: 0 8px 24px rgba(0,0,0,0.4); }
    .dropdown-content a { color: var(--text-main); padding: 10px 16px; text-decoration: none; display: flex; align-items: center; gap: 10px; font-size: 13px; transition: background 0.2s; }
    .dropdown-content a:hover { background-color: var(--bg-color); color: var(--secondary); }
    .dropdown-content a.danger:hover { color: var(--danger); }
</style>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Customer</h1>
        <div class="breadcrumb">
            <span>Siliwangi Admin</span>
            <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
            <span style="color: var(--secondary)">Manajemen Pelanggan</span>
        </div>
    </div>
    <div class="header-actions">
        <button class="btn btn-outline" style="margin-right: 8px;">
            <i class="fas fa-file-export"></i> Ekspor CSV
        </button>
        <button class="btn btn-gold">
            <i class="fas fa-user-plus"></i> Tambah Customer
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-info">
            <p>Total Customer</p>
            <h4>{{ number_format($stats['total_customers']) }}</h4>
        </div>
        <div class="summary-icon" style="background: var(--info-bg); color: var(--info-text);">
            <i class="fas fa-users"></i>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-info">
            <p>Customer Aktif (Bulan Ini)</p>
            <h4>{{ number_format($stats['active_customers']) }}</h4>
        </div>
        <div class="summary-icon" style="background: var(--success-bg); color: var(--success-text);">
            <i class="fas fa-user-check"></i>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-info">
            <p>Customer Baru (30 Hari)</p>
            <h4>+{{ number_format($stats['new_customers']) }}</h4>
        </div>
        <div class="summary-icon" style="background: var(--purple-bg); color: var(--purple-text);">
            <i class="fas fa-user-plus"></i>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-info">
            <p>Blacklist</p>
            <h4>{{ number_format($stats['blacklist_customers']) }}</h4>
        </div>
        <div class="summary-icon" style="background: var(--danger-bg); color: var(--danger-text);">
            <i class="fas fa-user-slash"></i>
        </div>
    </div>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 16px;">
        <div class="search-box" style="display: block; width: 300px;">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Cari nama, NIK, atau nomor telepon...">
        </div>
        <div>
            <select class="btn btn-outline" style="padding: 8px 12px; border-radius: 6px; font-size: 13px;">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="blacklist">Daftar Hitam</option>
            </select>
        </div>
    </div>

    <div class="table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Kontak</th>
                    <th>Bergabung</th>
                    <th>Total Rental</th>
                    <th>Total Transaksi</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td onclick="openDrawer({{ $customer->id }})">
                        <div class="customer-cell">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=F3F4F6&color=1F2937" alt="{{ $customer->name }}" class="customer-avatar">
                            <div>
                                <div style="font-weight: 600;">{{ $customer->name }}</div>
                                <div style="font-size: 11px; color: var(--text-muted); font-family: monospace;">NIK: {{ $customer->nik }}</div>
                            </div>
                        </div>
                    </td>
                    <td onclick="openDrawer({{ $customer->id }})">
                        <div>{{ $customer->phone }}</div>
                        <div style="font-size: 11px; color: var(--text-muted)">{{ $customer->email }}</div>
                    </td>
                    <td onclick="openDrawer({{ $customer->id }})">{{ $customer->created_at->format('d M Y') }}</td>
                    <td onclick="openDrawer({{ $customer->id }})"><span style="font-weight: 600;">{{ $customer->bookings_count }}x</span></td>
                    <td onclick="openDrawer({{ $customer->id }})">Rp {{ number_format($customer->total_transaction, 0, ',', '.') }}</td>
                    <td onclick="openDrawer({{ $customer->id }})">
                        @if($customer->status == 'active')
                            <span class="badge badge-success">Aktif</span>
                        @elseif($customer->status == 'blacklist')
                            <span class="badge badge-danger">Daftar Hitam</span>
                        @else
                            <span class="badge badge-info">Baru</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div class="action-dropdown">
                            <button class="action-btn" onclick="toggleDropdown('drop{{ $customer->id }}', event)"><i class="fas fa-ellipsis-v"></i></button>
                            <div class="dropdown-content" id="drop{{ $customer->id }}" style="text-align: left;">
                                <a href="#" onclick="openDrawer({{ $customer->id }})"><i class="fas fa-eye"></i> Lihat Profil</a>
                                <a href="#"><i class="fas fa-history"></i> Riwayat Rental</a>
                                <a href="#" class="danger" onclick="toggleBlacklist({{ $customer->id }})"><i class="fas fa-ban"></i> Blacklist</a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $customers->links() }}
    </div>
</div>

<!-- SIDE DRAWER CUSTOMER DETAIL -->
<div class="drawer-overlay" id="drawerOverlay" onclick="closeDrawer()"></div>
<div class="drawer" id="customerDrawer">
    <div class="drawer-header">
        <div style="display: flex; gap: 12px; align-items: center;">
            <h2 class="modal-title">Profil Pelanggan</h2>
            <span class="badge badge-success">Aktif</span>
        </div>
        <button class="close-drawer" onclick="closeDrawer()"><i class="fas fa-times"></i></button>
    </div>
    <div class="drawer-body">
        
        <div class="profile-header">
            <img src="https://ui-avatars.com/api/?name=Andi+Wijaya&background=111827&color=D4AF37&size=128" alt="Andi" class="profile-avatar">
            <div class="profile-name">Andi Wijaya</div>
            <div style="color: var(--text-muted); font-size: 13px;">Pelanggan sejak {{ $customer->created_at->format('d M Y') }}</div>
        </div>

        <div class="profile-stats">
            <div class="stat-box">
                <div class="val">8</div>
                <div class="lbl">Total Sewa</div>
            </div>
            <div class="stat-box">
                <div class="val">Rp 12.5M</div>
                <div class="lbl">Total Transaksi</div>
            </div>
        </div>

        <div class="info-group">
            <h4><i class="fas fa-address-card"></i> Data Pribadi</h4>
            <div class="info-row">
                <span class="lbl">NIK</span>
                <span class="val" style="font-family: monospace;">3273123456789001</span>
            </div>
            <div class="info-row">
                <span class="lbl">No. Telepon / WA</span>
                <span class="val">0856-7890-1234</span>
            </div>
            <div class="info-row">
                <span class="lbl">Email</span>
                <span class="val">andi.w@example.com</span>
            </div>
            <div class="info-row">
                <span class="lbl">Alamat Lengkap</span>
                <span class="val" style="text-align: right; max-width: 60%;">Jl. Setiabudi No. 123, Sukasari, Kota Bandung, Jawa Barat 40153</span>
            </div>
        </div>

        <div class="info-group">
            <h4><i class="fas fa-id-badge"></i> Dokumen Verifikasi</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600;">KTP</span>
                    <img src="https://images.unsplash.com/photo-1620054707198-1004a43425a4?auto=format&fit=crop&w=300&q=80" alt="KTP" class="doc-preview">
                </div>
                <div>
                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600;">SIM A</span>
                    <img src="https://images.unsplash.com/photo-1589828131846-591745eb968a?auto=format&fit=crop&w=300&q=80" alt="SIM" class="doc-preview">
                </div>
            </div>
        </div>

        <div class="info-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid var(--card-border); padding-bottom: 8px;">
                <h4 style="margin: 0; border: none; padding: 0;"><i class="fas fa-history"></i> Sewa Terakhir</h4>
                <a href="#" style="font-size: 11px; color: var(--secondary); font-weight: 600;">Lihat Semua</a>
            </div>
            
            <div style="background: var(--bg-color); border: 1px solid var(--card-border); border-radius: var(--radius-sm); padding: 12px; margin-bottom: 8px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-family: monospace; font-size: 12px; font-weight: 600;">BK-2605-002</span>
                    <span class="badge badge-warning" style="font-size: 10px; padding: 2px 6px;">Menunggu</span>
                </div>
                <div style="font-weight: 600; font-size: 13px;">Toyota Innova Zenix</div>
                <div style="font-size: 11px; color: var(--text-muted);">08 Mei 2026 - 10 Mei 2026 (3 Hari)</div>
            </div>

            <div style="background: var(--bg-color); border: 1px solid var(--card-border); border-radius: var(--radius-sm); padding: 12px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-family: monospace; font-size: 12px; font-weight: 600;">BK-2512-045</span>
                    <span class="badge badge-success" style="font-size: 10px; padding: 2px 6px;">Selesai</span>
                </div>
                <div style="font-weight: 600; font-size: 13px;">Hyundai Palisade</div>
                <div style="font-size: 11px; color: var(--text-muted);">24 Des 2025 - 26 Des 2025 (2 Hari)</div>
            </div>
        </div>

        <button class="btn btn-outline" style="width: 100%; color: var(--danger); border-color: var(--danger);" onclick="toggleBlacklist()">
            <i class="fas fa-ban"></i> Masukkan ke Daftar Hitam
        </button>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Dropdown Logic
    function toggleDropdown(id, event) {
        event.stopPropagation();
        document.querySelectorAll('.dropdown-content').forEach(el => {
            if (el.id !== id) el.parentElement.classList.remove('active');
        });
        document.getElementById(id).parentElement.classList.toggle('active');
    }

    window.addEventListener('click', function(e) {
        if (!e.target.matches('.action-btn') && !e.target.matches('.fa-ellipsis-v')) {
            document.querySelectorAll('.action-dropdown').forEach(el => {
                el.classList.remove('active');
            });
        }
    });

    // Drawer Logic
    function openDrawer() {
        document.getElementById('drawerOverlay').classList.add('show');
    }

    function closeDrawer() {
        document.getElementById('drawerOverlay').classList.remove('show');
    }

    // SweetAlert Blacklist
    function toggleBlacklist() {
        // If coming from action menu, stop propagation to drawer
        if(event) event.stopPropagation(); 
        
        Swal.fire({
            title: 'Blokir Pelanggan?',
            text: "Pelanggan tidak akan bisa melakukan pemesanan lagi.",
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Tuliskan alasan pemblokiran...',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Blokir',
            cancelButtonText: 'Batal',
            background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1E293B' : '#FFFFFF',
            color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#F3F4F6' : '#1F2937'
        }).then((result) => {
            if (result.isConfirmed) {
                closeDrawer();
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Pelanggan telah dimasukkan ke daftar hitam.',
                    icon: 'success',
                    background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1E293B' : '#FFFFFF',
                    color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#F3F4F6' : '#1F2937'
                });
            }
        });
    }
</script>
@endsection
