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
            <button class="btn btn-gold" onclick="openModal('addUserModal')" style="padding: 12px 24px; border-radius: 14px;">
                <i class="fas fa-plus"></i> Tambah User Baru
            </button>
        </div>
    </div>

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
                                <button class="action-btn" title="Edit User Profile">
                                    <i class="fas fa-pen-nib"></i>
                                </button>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn action-btn-delete" title="Delete User">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
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

@endsection

