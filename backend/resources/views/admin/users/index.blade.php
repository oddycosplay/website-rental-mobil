@extends('layouts.admin')

@section('title', 'Manajemen User - Siliwangi Admin')

@section('styles')
<style>
    .animate-delay-1 { animation-delay: 0.1s; }
    .animate-delay-2 { animation-delay: 0.2s; }
    .animate-delay-3 { animation-delay: 0.3s; }

    .user-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }
    
    .stat-card-premium {
        position: relative;
        padding: 28px;
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-xl);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: var(--card-shadow);
    }
    
    .stat-card-premium:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-shadow-hover);
        border-color: var(--secondary);
    }
    
    .stat-card-premium::before {
        content: '';
        position: absolute;
        top: -50%; right: -50%;
        width: 150px; height: 150px;
        background: radial-gradient(circle, var(--secondary-glow) 0%, transparent 70%);
        opacity: 0.3;
        transition: all 0.5s;
    }
    
    .stat-card-premium:hover::before {
        transform: scale(1.5);
        opacity: 0.5;
    }

    .stat-icon-wrapper {
        width: 56px; height: 56px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px;
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }
    
    .stat-label {
        font-size: 13px;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: var(--text-main);
        font-family: 'Poppins', sans-serif;
    }
    
    .stat-trend {
        margin-top: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Table Enhancements */
    .table-card-premium {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }
    
    .premium-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .premium-table th {
        padding: 18px 24px;
        background: var(--bg-color);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        border-bottom: 1px solid var(--card-border);
    }
    
    .premium-table td {
        padding: 20px 24px;
        border-bottom: 1px solid var(--card-border);
        vertical-align: middle;
        transition: all 0.2s;
    }
    
    .premium-table tr:last-child td { border-bottom: none; }
    
    .premium-table tr:hover td {
        background: rgba(var(--secondary-glow), 0.02);
    }

    .avatar-wrapper {
        position: relative;
        width: 44px; height: 44px;
    }
    
    .avatar-main {
        width: 100%; height: 100%;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--secondary), #A88B1D);
        color: var(--primary);
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 16px;
        box-shadow: 0 4px 10px rgba(212, 175, 55, 0.2);
    }
    
    .status-dot {
        position: absolute;
        bottom: -2px; right: -2px;
        width: 12px; height: 12px;
        border-radius: 50%;
        border: 2px solid var(--card-bg);
    }
    .status-dot-active { background: var(--success); }
    
    .user-name-main {
        font-weight: 700;
        color: var(--text-main);
        font-size: 15px;
    }
    
    .user-email-sub {
        font-size: 12px;
        color: var(--text-muted);
    }

    .pill-badge {
        padding: 6px 14px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .pill-role {
        background: var(--info-bg);
        color: var(--info-text);
        border: 1px solid rgba(59, 130, 246, 0.2);
    }
    
    .pill-status {
        position: relative;
        padding-left: 20px;
    }
    .pill-status::before {
        content: '';
        position: absolute;
        left: 8px;
        width: 6px; height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .action-btn-group {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }
    
    .action-btn {
        width: 36px; height: 36px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--card-border);
        color: var(--text-muted);
        transition: all 0.2s;
        background: var(--bg-color);
    }
    
    .action-btn:hover {
        border-color: var(--secondary);
        color: var(--secondary);
        transform: translateY(-2px);
    }
    
    .action-btn-delete:hover {
        border-color: var(--danger);
        color: var(--danger);
    }

    /* Modal Styles */
    .modal-overlay {
        display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 1000;
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
    .modal-title { font-size: 1.2rem; font-weight: 700; color: var(--text-main); }
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
    
    /* Premium Radio Buttons for Access Column */
    .fitur-akses-group {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }
    .akses-radio-label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        color: var(--text-muted);
        cursor: not-allowed;
        background: rgba(255, 255, 255, 0.02);
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid var(--card-border);
        transition: all 0.2s ease;
        user-select: none;
    }
    .akses-radio-label input[type="radio"] {
        margin: 0;
        accent-color: var(--secondary);
        cursor: not-allowed;
    }
    .akses-radio-label:has(input:checked) {
        color: var(--secondary);
        background: rgba(212, 175, 55, 0.08);
        border-color: rgba(212, 175, 55, 0.3);
        font-weight: 600;
    }
    .pill-badge-detail {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        font-size: 11px;
        font-weight: 500;
        border-radius: 4px;
        white-space: nowrap;
    }
</style>
@endsection

@section('content')

<div class="animate-fade-in">
    <div class="page-header">
        <div>
            <h1 class="page-title">Manajemen User</h1>
            <div class="breadcrumb">
                <span>Siliwangi Admin</span>
                <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
                <span style="color: var(--secondary)">User Management</span>
            </div>
        </div>
        <div class="header-actions">
            @if(auth()->user()->hasRole('super-admin'))
                <button class="btn btn-gold" onclick="openModal('addUserModal')" style="padding: 12px 24px; border-radius: 14px;">
                    <i class="fas fa-plus"></i> Tambah User Baru
                </button>
            @else
                <button class="btn btn-gold" style="padding: 12px 24px; border-radius: 14px; opacity: 0.6; cursor: not-allowed;" disabled title="Hanya Super Admin yang dapat menambahkan user baru">
                    <i class="fas fa-plus"></i> Tambah User Baru
                </button>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md); margin-bottom: 24px; background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); color: #10B981; border-left: 4px solid #10B981;">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md); margin-bottom: 24px; background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2); color: #EF4444; border-left: 4px solid #EF4444;">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: var(--radius-md); margin-bottom: 24px; background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2); color: #EF4444; border-left: 4px solid #EF4444;">
        <div class="d-flex align-items-center mb-2">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Ada kesalahan pengisian form:</strong>
        </div>
        <ul style="margin: 0; padding-left: 20px; font-size: 14px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="user-stats-grid">
        <div class="stat-card-premium animate-fade-in animate-delay-1">
            <div class="stat-icon-wrapper" style="background: var(--success-bg); color: var(--success);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-label">Total User</div>
            <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-trend" style="color: var(--success);">
                <i class="fas fa-arrow-up"></i>
                <span>12% Increase</span>
            </div>
        </div>
        
        <div class="stat-card-premium animate-fade-in animate-delay-2">
            <div class="stat-icon-wrapper" style="background: rgba(212, 175, 55, 0.1); color: var(--secondary);">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-label">User Aktif</div>
            <div class="stat-value">{{ number_format($stats['active_users']) }}</div>
            <div class="stat-trend" style="color: var(--info);">
                <i class="fas fa-circle" style="font-size: 8px;"></i>
                <span>Stable connectivity</span>
            </div>
        </div>
        
        <div class="stat-card-premium animate-fade-in animate-delay-3">
            <div class="stat-icon-wrapper" style="background: var(--info-bg); color: var(--info);">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="stat-label">User Baru (Bulan Ini)</div>
            <div class="stat-value">+{{ number_format($stats['new_this_month']) }}</div>
            <div class="stat-trend" style="color: var(--success);">
                <i class="fas fa-chart-line"></i>
                <span>Higher than average</span>
            </div>
        </div>
    </div>

    <div class="table-card-premium animate-fade-in animate-delay-3">
        <div class="table-container" style="margin: 0;">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>User & Identitas</th>
                        <th>Role</th>
                        <th>Fitur Akses</th>
                        <th>Rincian Fitur Akses</th>
                        <th>Status</th>
                        <th>Waktu Terdaftar</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div class="avatar-wrapper">
                                    <div class="avatar-main">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div class="status-dot {{ $user->status == 'active' ? 'status-dot-active' : '' }}"></div>
                                </div>
                                <div>
                                    <div class="user-name-main">{{ $user->name }}</div>
                                    <div class="user-email-sub">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                @foreach($user->roles as $role)
                                <span class="pill-badge pill-role">
                                    <i class="fas fa-shield-alt" style="font-size: 10px;"></i>
                                    {{ strtoupper($role->name) }}
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="fitur-akses-group">
                                @php
                                    $userRole = $user->roles->first()?->name;
                                @endphp
                                <label class="akses-radio-label">
                                    <input type="radio" name="akses_{{ $user->id }}" value="administrator" {{ in_array($userRole, ['super-admin', 'admin']) ? 'checked' : '' }} disabled>
                                    Administrator
                                </label>
                                <label class="akses-radio-label">
                                    <input type="radio" name="akses_{{ $user->id }}" value="owner" {{ $userRole === 'owner' ? 'checked' : '' }} disabled>
                                    Owner
                                </label>
                                <label class="akses-radio-label">
                                    <input type="radio" name="akses_{{ $user->id }}" value="finance" {{ $userRole === 'finance' ? 'checked' : '' }} disabled>
                                    Keuangan
                                </label>
                                <label class="akses-radio-label">
                                    <input type="radio" name="akses_{{ $user->id }}" value="driver" {{ $userRole === 'driver' ? 'checked' : '' }} disabled>
                                    Driver
                                </label>
                                <label class="akses-radio-label">
                                    <input type="radio" name="akses_{{ $user->id }}" value="operasional" {{ $userRole === 'operasional' ? 'checked' : '' }} disabled>
                                    Operasional
                                </label>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; flex-wrap: wrap; gap: 4px; max-width: 250px;">
                                @if($userRole === 'super-admin')
                                    <span class="pill-badge-detail" style="background: rgba(16, 185, 129, 0.1); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.2);">
                                        <i class="fas fa-check-double" style="margin-right: 4px;"></i> Semua Modul (Full)
                                    </span>
                                    <span class="pill-badge-detail" style="background: rgba(16, 185, 129, 0.1); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.2);">
                                        Manajemen Karyawan
                                    </span>
                                @elseif($userRole === 'admin')
                                    <span class="pill-badge-detail" style="background: rgba(16, 185, 129, 0.1); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.2);">
                                        <i class="fas fa-check-double" style="margin-right: 4px;"></i> Semua Modul (Full)
                                    </span>
                                @elseif($userRole === 'owner')
                                    <span class="pill-badge-detail" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6; border: 1px solid rgba(59, 130, 246, 0.2);">
                                        Laporan Keuangan
                                    </span>
                                    <span class="pill-badge-detail" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6; border: 1px solid rgba(59, 130, 246, 0.2);">
                                        Dashboard Analytics
                                    </span>
                                @elseif($userRole === 'finance')
                                    <span class="pill-badge-detail" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6; border: 1px solid rgba(59, 130, 246, 0.2);">
                                        Pembayaran
                                    </span>
                                    <span class="pill-badge-detail" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6; border: 1px solid rgba(59, 130, 246, 0.2);">
                                        Pengeluaran
                                    </span>
                                @elseif($userRole === 'driver')
                                    <span class="pill-badge-detail" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.2);">
                                        Jadwal Driver
                                    </span>
                                    <span class="pill-badge-detail" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.2);">
                                        Status Kehadiran
                                    </span>
                                @elseif($userRole === 'operasional')
                                    <span class="pill-badge-detail" style="background: rgba(139, 92, 246, 0.1); color: #8B5CF6; border: 1px solid rgba(139, 92, 246, 0.2);">
                                        Kelola Mobil
                                    </span>
                                    <span class="pill-badge-detail" style="background: rgba(139, 92, 246, 0.1); color: #8B5CF6; border: 1px solid rgba(139, 92, 246, 0.2);">
                                        Jadwal Mobil
                                    </span>
                                    <span class="pill-badge-detail" style="background: rgba(139, 92, 246, 0.1); color: #8B5CF6; border: 1px solid rgba(139, 92, 246, 0.2);">
                                        Survei Lokasi
                                    </span>
                                @else
                                    <span class="pill-badge-detail" style="background: rgba(107, 114, 128, 0.1); color: #9CA3AF; border: 1px solid rgba(107, 114, 128, 0.2);">
                                        Akses Terbatas
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="pill-badge pill-status" style="background: {{ $user->status == 'active' ? 'var(--success-bg)' : 'var(--danger-bg)' }}; color: {{ $user->status == 'active' ? 'var(--success-text)' : 'var(--danger-text)' }};">
                                {{ strtoupper($user->status) }}
                            </span>
                        </td>
                        <td>
                            <div style="color: var(--text-main); font-weight: 500;">{{ $user->created_at->format('d M Y') }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">{{ $user->created_at->diffForHumans() }}</div>
                        </td>
                        <td>
                            <div class="action-btn-group">
                                @if(auth()->user()->hasRole('super-admin'))
                                    <button class="action-btn" title="Edit User Profile" onclick="openEditModal({{ json_encode($user) }}, '{{ $user->roles->first()?->name }}')">
                                        <i class="fas fa-pen-nib"></i>
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" title="Delete User">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="action-btn" style="opacity: 0.5; cursor: not-allowed;" disabled title="Hanya Super Admin yang dapat mengedit user">
                                        <i class="fas fa-pen-nib"></i>
                                    </button>
                                    <button class="action-btn" style="opacity: 0.5; cursor: not-allowed;" disabled title="Hanya Super Admin yang dapat menghapus user">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div style="padding: 20px 24px; border-top: 1px solid var(--card-border); background: var(--bg-color);">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

<!-- ADD USER MODAL -->
<div class="modal-overlay" id="addUserModal">
    <div class="modal-content animate__animated animate__fadeInUp animate__faster">
        <div class="modal-header">
            <h2 class="modal-title"><i class="fas fa-user-plus me-2" style="color: var(--secondary);"></i> Tambah Karyawan Baru</h2>
            <i class="fas fa-times close-modal" onclick="closeModal('addUserModal')"></i>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan alamat email" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password (min 8 karakter)" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Role Karyawan</label>
                    <select name="role" id="add_role" class="form-control" onchange="updateAccessInfo('add')" required>
                        <option value="" disabled selected>Pilih Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ strtoupper($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Fitur Akses</label>
                    <div class="fitur-akses-group" style="border: 1px solid rgba(255,255,255,0.08); padding: 12px; border-radius: 8px; background: rgba(0,0,0,0.1); display: flex; flex-wrap: wrap; gap: 10px;">
                        <label class="akses-radio-label">
                            <input type="radio" name="add_akses" value="administrator" onchange="syncRoleFromAccess('add', 'admin')">
                            Administrator
                        </label>
                        <label class="akses-radio-label">
                            <input type="radio" name="add_akses" value="owner" onchange="syncRoleFromAccess('add', 'owner')">
                            Owner
                        </label>
                        <label class="akses-radio-label">
                            <input type="radio" name="add_akses" value="finance" onchange="syncRoleFromAccess('add', 'finance')">
                            Keuangan
                        </label>
                        <label class="akses-radio-label">
                            <input type="radio" name="add_akses" value="driver" onchange="syncRoleFromAccess('add', 'driver')">
                            Driver
                        </label>
                        <label class="akses-radio-label">
                            <input type="radio" name="add_akses" value="operasional" onchange="syncRoleFromAccess('add', 'operasional')">
                            Operasional
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Rincian Fitur Akses</label>
                    <div id="add_rincian_fitur" class="modal-rincian-fitur" style="display: flex; flex-wrap: wrap; gap: 6px; padding: 10px; border-radius: 8px; background: rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.08); min-height: 42px; align-items: center;">
                        <span class="pill-badge-detail" style="background: rgba(107, 114, 128, 0.1); color: #9CA3AF; border: 1px solid rgba(107, 114, 128, 0.2);">
                            Pilih role untuk melihat rincian akses
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addUserModal')">Batal</button>
                <button type="submit" class="btn btn-gold">Simpan Karyawan</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT USER MODAL -->
<div class="modal-overlay" id="editUserModal">
    <div class="modal-content animate__animated animate__fadeInUp animate__faster">
        <div class="modal-header">
            <h2 class="modal-title"><i class="fas fa-user-edit me-2" style="color: var(--secondary);"></i> Edit Profile Karyawan</h2>
            <i class="fas fa-times close-modal" onclick="closeModal('editUserModal')"></i>
        </div>
        <form id="editUserForm" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status Akun</label>
                    <select name="status" id="edit_status" class="form-control" required>
                        <option value="active">ACTIVE</option>
                        <option value="inactive">INACTIVE</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Role Karyawan</label>
                    <select name="role" id="edit_role" class="form-control" onchange="updateAccessInfo('edit')" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ strtoupper($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Fitur Akses</label>
                    <div class="fitur-akses-group" style="border: 1px solid rgba(255,255,255,0.08); padding: 12px; border-radius: 8px; background: rgba(0,0,0,0.1); display: flex; flex-wrap: wrap; gap: 10px;">
                        <label class="akses-radio-label">
                            <input type="radio" name="edit_akses" value="administrator" onchange="syncRoleFromAccess('edit', 'admin')">
                            Administrator
                        </label>
                        <label class="akses-radio-label">
                            <input type="radio" name="edit_akses" value="owner" onchange="syncRoleFromAccess('edit', 'owner')">
                            Owner
                        </label>
                        <label class="akses-radio-label">
                            <input type="radio" name="edit_akses" value="finance" onchange="syncRoleFromAccess('edit', 'finance')">
                            Keuangan
                        </label>
                        <label class="akses-radio-label">
                            <input type="radio" name="edit_akses" value="driver" onchange="syncRoleFromAccess('edit', 'driver')">
                            Driver
                        </label>
                        <label class="akses-radio-label">
                            <input type="radio" name="edit_akses" value="operasional" onchange="syncRoleFromAccess('edit', 'operasional')">
                            Operasional
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Rincian Fitur Akses</label>
                    <div id="edit_rincian_fitur" class="modal-rincian-fitur" style="display: flex; flex-wrap: wrap; gap: 6px; padding: 10px; border-radius: 8px; background: rgba(0,0,0,0.1); border: 1px solid rgba(255,255,255,0.08); min-height: 42px; align-items: center;">
                        <!-- Dynamic content -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('editUserModal')">Batal</button>
                <button type="submit" class="btn btn-gold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    // Handle closing alert automatically or manually
    document.querySelectorAll('.btn-close').forEach(button => {
        button.addEventListener('click', function() {
            this.parentElement.remove();
        });
    });

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        document.body.style.overflow = '';
    }

    const roleAccessMap = {
        'super-admin': {
            akses: 'administrator',
            rincian: `
                <span class="pill-badge-detail" style="background: rgba(16, 185, 129, 0.1); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.2); font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                    <i class="fas fa-check-double" style="margin-right: 4px;"></i> Semua Modul (Full)
                </span>
                <span class="pill-badge-detail" style="background: rgba(16, 185, 129, 0.1); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.2); font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                    Manajemen Karyawan
                </span>
            `
        },
        'admin': {
            akses: 'administrator',
            rincian: `
                <span class="pill-badge-detail" style="background: rgba(16, 185, 129, 0.1); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.2); font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                    <i class="fas fa-check-double" style="margin-right: 4px;"></i> Semua Modul (Full)
                </span>
            `
        },
        'owner': {
            akses: 'owner',
            rincian: `
                <span class="pill-badge-detail" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6; border: 1px solid rgba(59, 130, 246, 0.2); font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                    Laporan Keuangan
                </span>
                <span class="pill-badge-detail" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6; border: 1px solid rgba(59, 130, 246, 0.2); font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                    Dashboard Analytics
                </span>
            `
        },
        'finance': {
            akses: 'finance',
            rincian: `
                <span class="pill-badge-detail" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6; border: 1px solid rgba(59, 130, 246, 0.2); font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                    Pembayaran
                </span>
                <span class="pill-badge-detail" style="background: rgba(59, 130, 246, 0.1); color: #3B82F6; border: 1px solid rgba(59, 130, 246, 0.2); font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                    Pengeluaran
                </span>
            `
        },
        'driver': {
            akses: 'driver',
            rincian: `
                <span class="pill-badge-detail" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.2); font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                    Jadwal Driver
                </span>
                <span class="pill-badge-detail" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.2); font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                    Status Kehadiran
                </span>
            `
        },
        'operasional': {
            akses: 'operasional',
            rincian: `
                <span class="pill-badge-detail" style="background: rgba(139, 92, 246, 0.1); color: #8B5CF6; border: 1px solid rgba(139, 92, 246, 0.2); font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                    Kelola Mobil
                </span>
                <span class="pill-badge-detail" style="background: rgba(139, 92, 246, 0.1); color: #8B5CF6; border: 1px solid rgba(139, 92, 246, 0.2); font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                    Jadwal Mobil
                </span>
                <span class="pill-badge-detail" style="background: rgba(139, 92, 246, 0.1); color: #8B5CF6; border: 1px solid rgba(139, 92, 246, 0.2); font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                    Survei Lokasi
                </span>
            `
        }
    };

    function updateAccessInfo(type) {
        const roleSelect = document.getElementById(type + '_role');
        const rincianDiv = document.getElementById(type + '_rincian_fitur');
        const radios = document.getElementsByName(type + '_akses');
        
        if (!roleSelect || !rincianDiv || !radios) return;
        
        const selectedRole = roleSelect.value;
        const info = roleAccessMap[selectedRole];
        
        if (info) {
            radios.forEach(radio => {
                radio.checked = (radio.value === info.akses);
            });
            rincianDiv.innerHTML = info.rincian;
        } else {
            radios.forEach(radio => {
                radio.checked = false;
            });
            rincianDiv.innerHTML = `
                <span class="pill-badge-detail" style="background: rgba(107, 114, 128, 0.1); color: #9CA3AF; border: 1px solid rgba(107, 114, 128, 0.2); font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                    Pilih role untuk melihat rincian akses
                </span>
            `;
        }
    }
    
    function syncRoleFromAccess(type, role) {
        const roleSelect = document.getElementById(type + '_role');
        if (roleSelect) {
            if (role === 'admin') {
                if (roleSelect.value !== 'super-admin' && roleSelect.value !== 'admin') {
                    roleSelect.value = 'admin';
                }
            } else {
                roleSelect.value = role;
            }
            updateAccessInfo(type);
        }
    }

    function openEditModal(user, currentRole) {
        document.getElementById('editUserForm').action = `/dashboard/users/${user.id}`;
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_status').value = user.status;
        document.getElementById('edit_role').value = currentRole;
        updateAccessInfo('edit');
        openModal('editUserModal');
    }

    // Close modal when clicking on overlay
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });
</script>
@endsection

