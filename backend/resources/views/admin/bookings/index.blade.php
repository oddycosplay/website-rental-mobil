@extends('layouts.admin')

@section('title', 'Booking & Schedule – Siliwangi Admin')

@section('styles')
<style>
    /* Layout & Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 900;
        color: var(--text-main);
        font-family: 'Poppins', sans-serif;
        letter-spacing: -0.5px;
        margin: 0;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        font-weight: 600;
        color: var(--text-muted);
        margin-top: 4px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    /* Summary Cards */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .stat-card-premium {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-lg);
        padding: 24px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        box-shadow: var(--card-shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card-premium:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow-hover);
        border-color: var(--secondary);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--text-muted);
        margin-bottom: 6px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 900;
        color: var(--text-main);
        font-family: 'Poppins', sans-serif;
    }

    .stat-unit {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-left: 4px;
    }

    /* Table & Cards */
    .table-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--card-shadow);
        margin-bottom: 32px;
    }

    .table-card-head {
        padding: 28px 32px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255, 255, 255, 0.02);
    }

    .table-title-wrap h3 {
        font-size: 18px;
        font-weight: 900;
        color: var(--text-main);
        margin: 0;
        letter-spacing: -0.3px;
    }

    .table-title-wrap p {
        font-size: 11px;
        color: var(--text-muted);
        font-weight: 600;
        margin-top: 4px;
    }

    .search-input-wrap {
        position: relative;
        width: 280px;
    }

    .search-input {
        width: 100%;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        padding: 10px 16px 10px 40px;
        font-size: 12px;
        color: var(--text-main);
        transition: all 0.2s;
    }

    .search-input:focus {
        border-color: var(--secondary);
        background: rgba(255, 255, 255, 0.05);
        outline: none;
    }

    .search-input-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 12px;
        color: var(--text-muted);
    }

    /* Admin Table */
    .admin-table-wrap {
        overflow-x: auto;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    .admin-table th {
        padding: 16px 32px;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: var(--text-muted);
        background: rgba(255, 255, 255, 0.01);
        border-bottom: 1px solid var(--card-border);
        text-align: left;
    }

    .admin-table td {
        padding: 20px 32px;
        border-bottom: 1px solid var(--card-border);
        vertical-align: middle;
    }

    .admin-table tr {
        transition: background 0.2s;
    }

    .admin-table tr:hover {
        background: rgba(212, 175, 55, 0.03);
    }

    /* Cells Styling */
    .booking-code-chip {
        font-family: 'JetBrains Mono', monospace;
        font-size: 10px;
        font-weight: 800;
        color: var(--secondary);
        background: rgba(212, 175, 55, 0.1);
        padding: 4px 10px;
        border-radius: 6px;
        border: 1px solid rgba(212, 175, 55, 0.2);
    }

    .customer-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .customer-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        object-fit: cover;
        border: 2px solid var(--card-border);
    }

    .customer-name {
        font-size: 13px;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1.2;
    }

    .customer-phone {
        font-size: 10px;
        color: var(--text-muted);
        font-weight: 700;
        margin-top: 2px;
    }

    .asset-cell {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .car-name {
        font-size: 13px;
        font-weight: 800;
        color: var(--text-main);
    }

    .car-badge-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .plate-badge {
        font-size: 9px;
        font-weight: 900;
        color: var(--secondary);
        background: rgba(212, 175, 55, 0.1);
        padding: 2px 6px;
        border-radius: 4px;
    }

    .service-type {
        font-size: 9px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
    }

    .time-cell {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .time-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        font-weight: 700;
        color: var(--text-main);
    }

    .time-item i {
        font-size: 10px;
        width: 14px;
        text-align: center;
    }

    /* Action Buttons */
    .action-btn-circle {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--card-border);
        color: var(--text-muted);
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
    }

    .action-btn-circle:hover {
        background: rgba(212, 175, 55, 0.1);
        border-color: var(--secondary);
        color: var(--secondary);
        transform: rotate(15deg);
    }

    /* Modal Styling */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(10px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-overlay.show {
        display: flex;
    }

    .modal-content {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 32px;
        width: 100%;
        max-width: 960px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.6);
        animation: modalIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes modalIn {
        from {
            transform: scale(0.9) translateY(40px);
            opacity: 0;
        }

        to {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 32px 40px;
        border-bottom: 1px solid var(--card-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255, 255, 255, 0.02);
    }

    .modal-body {
        padding: 40px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 40px;
    }

    @media (max-width: 850px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }

    .info-section {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--card-border);
        border-radius: 24px;
        padding: 28px;
        margin-bottom: 28px;
    }

    .section-title {
        font-size: 11px;
        font-weight: 900;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .data-row {
        display: flex;
        justify-content: space-between;
        padding: 14px 0;
        border-bottom: 1px solid var(--card-border);
    }

    .data-row:last-child {
        border-bottom: none;
    }

    .data-label {
        font-size: 12px;
        color: var(--text-muted);
        font-weight: 600;
    }

    .data-value {
        font-size: 13px;
        color: var(--text-main);
        font-weight: 800;
    }

    /* Timeline & Documents */
    .timeline {
        position: relative;
        padding-left: 32px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: var(--card-border);
    }

    .timeline-item {
        position: relative;
        padding-bottom: 24px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -26px;
        top: 4px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--card-bg);
        border: 3px solid var(--card-border);
        z-index: 2;
    }

    .timeline-item.active::before {
        border-color: var(--secondary);
        background: var(--secondary);
    }

    .timeline-item.done::before {
        border-color: var(--success);
        background: var(--success);
    }

    .doc-card {
        aspect-ratio: 16/10;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.03);
        border: 2px dashed var(--card-border);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--text-muted);
        transition: all 0.3s;
        cursor: pointer;
    }

    .doc-card:hover {
        border-color: var(--secondary);
        color: var(--secondary);
        background: rgba(212, 175, 55, 0.05);
    }

    .doc-card i {
        font-size: 32px;
        margin-bottom: 12px;
        opacity: 0.5;
    }

    /* Buttons Premium */
    .btn-premium-gold {
        background: linear-gradient(135deg, #D4AF37, #F1D18A);
        color: #0F172A;
        border: none;
        padding: 12px 28px;
        border-radius: 14px;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 10px 25px -5px rgba(212, 175, 55, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-premium-gold:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px -10px rgba(212, 175, 55, 0.4);
    }

    .btn-premium-glass {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--card-border);
        color: var(--text-main);
        padding: 12px 28px;
        border-radius: 14px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-premium-glass:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--secondary);
    }

    .pagination-wrap {
        padding: 32px;
        border-top: 1px solid var(--card-border);
    }

    /* Collapsible Advanced Filter Panel */
    #advancedFilterPanel {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-bottom: 0 solid transparent;
    }
    
    #advancedFilterPanel.show {
        max-height: 500px;
        border-bottom: 1px solid var(--card-border) !important;
    }
</style>
@endsection

@section('content')
<div class="page-header" data-aos="fade-down">
    <div>
        <h1 class="page-title">Booking Intelligence</h1>
        <div class="breadcrumb">
            <span>Operational Center</span>
            <i class="fas fa-chevron-right" style="font-size: 7px; opacity: 0.5;"></i>
            <span style="color: var(--secondary);">Reservation Management</span>
        </div>
    </div>
    <div class="header-actions">
        <button class="btn-premium-glass" onclick="toggleAdvancedFilter()">
            <i class="fas fa-filter" style="color: var(--secondary);"></i> Advanced Filter
        </button>
        <a href="{{ url('/admin/' . (auth()->user()->store_id ?? \App\Models\Store::first()?->id ?? 1) . '/bookings/bookings/create') }}" class="btn-premium-gold" style="text-decoration: none;">
            <i class="fas fa-plus-circle"></i> Manual Reservation
        </a>
    </div>
</div>

<!-- Summary Stats -->
<div class="summary-grid">
    <div class="stat-card-premium" data-aos="fade-up" data-aos-delay="0">
        <div class="stat-icon" style="background: var(--warning-bg); color: var(--warning-text);">
            <i class="fas fa-hourglass-half"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">Pending Verification</div>
            <div class="stat-value">{{ number_format($stats['pending']) }} <span class="stat-unit">Tickets</span></div>
        </div>
    </div>
    <div class="stat-card-premium" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-icon" style="background: var(--info-bg); color: var(--info-text);">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">Active Reservations</div>
            <div class="stat-value">{{ number_format($stats['total'] - $stats['pending']) }} <span class="stat-unit">Active</span></div>
        </div>
    </div>
    <div class="stat-card-premium" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8B5CF6;">
            <i class="fas fa-car-side"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">Ongoing Rentals</div>
            <div class="stat-value">{{ number_format($stats['ongoing']) }} <span class="stat-unit">Units</span></div>
        </div>
    </div>
    <div class="stat-card-premium" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-icon" style="background: var(--success-bg); color: var(--success-text);">
            <i class="fas fa-check-double"></i>
        </div>
        <div class="stat-info">
            <div class="stat-label">Completed Cycles</div>
            <div class="stat-value">{{ number_format($stats['completed']) }} <span class="stat-unit">Success</span></div>
        </div>
    </div>
</div>

<!-- Analytics Intelligence Section -->
<div class="row g-4 mb-5">
    <!-- Chart 1: Revenue & Order Trends (Line & Bar Chart) -->
    <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
        <div class="card h-100 p-4" style="background: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius-xl); box-shadow: var(--card-shadow); transition: var(--transition);">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 style="font-size: 16px; font-weight: 800; color: var(--text-main); margin: 0; font-family: 'Poppins', sans-serif;">
                        <i class="fas fa-chart-line text-warning me-2"></i> Revenue & Rental Analytics
                    </h4>
                    <p style="font-size: 11px; color: var(--text-muted); margin: 2px 0 0 0; font-weight: 600;">Monthly revenue growth and cumulative rental count</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge" style="background: rgba(212, 175, 55, 0.1); color: var(--secondary); font-size: 10px; font-weight: 700; padding: 6px 12px; border-radius: 8px;">Target: +25%</span>
                </div>
            </div>
            <div style="position: relative; height: 320px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart 2: Car Category Share (Doughnut Chart) -->
    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
        <div class="card h-100 p-4" style="background: var(--card-bg); border: 1px solid var(--card-border); border-radius: var(--radius-xl); box-shadow: var(--card-shadow); transition: var(--transition);">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 style="font-size: 16px; font-weight: 800; color: var(--text-main); margin: 0; font-family: 'Poppins', sans-serif;">
                        <i class="fas fa-chart-pie text-warning me-2"></i> Popular Car Demand
                    </h4>
                    <p style="font-size: 11px; color: var(--text-muted); margin: 2px 0 0 0; font-weight: 600;">Share distribution among top booked assets</p>
                </div>
            </div>
            <div style="position: relative; height: 250px;" class="d-flex align-items-center justify-content-center">
                <canvas id="CarShareChart"></canvas>
            </div>
            <div class="d-flex justify-content-around mt-4 pt-3" style="border-top: 1px solid var(--card-border);">
                <div class="text-center">
                    <div style="font-size: 9px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Self-Drive</div>
                    <div style="font-size: 15px; font-weight: 900; color: var(--success); font-family: 'Poppins', sans-serif; margin-top: 2px;">85%</div>
                </div>
                <div class="text-center" style="border-left: 1px solid var(--card-border); padding-left: 24px;">
                    <div style="font-size: 9px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">With Driver</div>
                    <div style="font-size: 15px; font-weight: 900; color: var(--secondary); font-family: 'Poppins', sans-serif; margin-top: 2px;">15%</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Data Table -->
<div class="table-card" data-aos="fade-up">
    <div class="table-card-head">
        <div class="table-title-wrap">
            <h3>Reservation Ledger</h3>
            <p>Unified view for all mobility requests</p>
        </div>
        <div class="search-input-wrap">
            <form method="GET" action="{{ route('admin.bookings.index') }}">
                @if(request('booking_status')) <input type="hidden" name="booking_status" value="{{ request('booking_status') }}"> @endif
                @if(request('payment_status')) <input type="hidden" name="payment_status" value="{{ request('payment_status') }}"> @endif
                @if(request('with_driver')) <input type="hidden" name="with_driver" value="{{ request('with_driver') }}"> @endif
                @if(request('store_id')) <input type="hidden" name="store_id" value="{{ request('store_id') }}"> @endif
                
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Reference, Name..." class="search-input">
            </form>
        </div>
    </div>

    <!-- Collapsible Advanced Filter panel -->
    <div class="filter-panel @if(request()->anyFilled(['booking_status', 'payment_status', 'with_driver', 'store_id'])) show @endif" id="advancedFilterPanel">
        <form method="GET" action="{{ route('admin.bookings.index') }}" id="filterForm">
            <input type="hidden" name="search" value="{{ request('search') }}">
            
            <div class="row g-3 p-4">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 10.5px;">Status Booking</label>
                    <select name="booking_status" class="form-select form-select-sm rounded-3 text-main" style="background: var(--card-bg); border-color: var(--card-border); padding: 10px;" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('booking_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('booking_status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="ongoing" {{ request('booking_status') === 'ongoing' ? 'selected' : '' }}>Active / Ongoing</option>
                        <option value="completed" {{ request('booking_status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('booking_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 10.5px;">Status Pembayaran</label>
                    <select name="payment_status" class="form-select form-select-sm rounded-3 text-main" style="background: var(--card-bg); border-color: var(--card-border); padding: 10px;" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="partial" {{ request('payment_status') === 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 10.5px;">Layanan Sopir</label>
                    <select name="with_driver" class="form-select form-select-sm rounded-3 text-main" style="background: var(--card-bg); border-color: var(--card-border); padding: 10px;" onchange="this.form.submit()">
                        <option value="">Semua Layanan</option>
                        <option value="0" {{ request('with_driver') === '0' ? 'selected' : '' }}>Lepas Kunci (Self-Drive)</option>
                        <option value="1" {{ request('with_driver') === '1' ? 'selected' : '' }}>Dengan Sopir (Driver)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px; font-size: 10.5px;">Cabang (Store)</label>
                    @php
                        $allStores = \App\Models\Store::all();
                    @endphp
                    <select name="store_id" class="form-select form-select-sm rounded-3 text-main" style="background: var(--card-bg); border-color: var(--card-border); padding: 10px;" onchange="this.form.submit()">
                        <option value="">Semua Cabang</option>
                        @foreach($allStores as $store)
                            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center p-3 border-top" style="border-color: var(--card-border); background: rgba(0,0,0,0.01);">
                <div class="small text-muted ps-2" style="font-size: 11px;">
                    @if(request()->anyFilled(['booking_status', 'payment_status', 'with_driver', 'store_id']))
                        <span class="badge bg-warning text-dark"><i class="fas fa-filter me-1"></i> Filter Aktif</span>
                    @else
                        Belum ada filter yang diterapkan
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-light btn-sm rounded-3 px-3 py-2 fw-semibold" style="font-size: 11.5px; border: 1px solid var(--card-border); text-decoration: none;">Reset Filter</a>
                    <button type="submit" class="btn btn-dark btn-sm rounded-3 px-3 py-2 fw-bold text-warning" style="font-size: 11.5px; background: var(--primary);">Terapkan</button>
                </div>
            </div>
        </form>
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Customer Entity</th>
                    <th>Allocated Asset</th>
                    <th>Service Window</th>
                    <th>Financial</th>
                    <th>Cycle</th>
                    <th style="text-align: right;">Ops</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>
                        <span class="booking-code-chip">{{ $booking->booking_code }}</span>
                    </td>
                    <td>
                        <div class="customer-cell">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($booking->customer->name ?? 'U') }}&background=0F172A&color=D4AF37&bold=true" class="customer-avatar">
                            <div>
                                <div class="customer-name">{{ $booking->customer->name ?? '-' }}</div>
                                <div class="customer-phone">{{ $booking->customer->phone ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="asset-cell">
                            <div class="car-name">{{ $booking->car->car_name ?? '-' }}</div>
                            <div class="car-badge-wrap">
                                <span class="plate-badge">{{ $booking->car->plate_number ?? '-' }}</span>
                                <span class="service-type">{{ $booking->with_driver ? 'Driver' : 'Self-Drive' }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="time-cell">
                            <div class="time-item">
                                <i class="fas fa-arrow-right-to-bracket" style="color: var(--success);"></i>
                                {{ $booking->pickup_date->translatedFormat('d M Y, H:i') }}
                            </div>
                            <div class="time-item">
                                <i class="fas fa-arrow-right-from-bracket" style="color: var(--danger);"></i>
                                {{ $booking->return_date->translatedFormat('d M Y, H:i') }}
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                        $pStatus = [
                        'paid' => ['var(--success-bg)', 'var(--success-text)', 'Paid'],
                        'partial' => ['var(--warning-bg)', 'var(--warning-text)', 'Partial'],
                        'unpaid' => ['var(--danger-bg)', 'var(--danger-text)', 'Unpaid'],
                        'refunded' => ['var(--info-bg)', 'var(--info-text)', 'Refunded'],
                        ][$booking->payment_status] ?? ['rgba(0,0,0,0.05)', 'var(--text-muted)', $booking->payment_status];
                        @endphp
                        <span style="--bg: {{ $pStatus[0] }}; --color: {{ $pStatus[1] }}; background: var(--bg); color: var(--color); padding: 4px 12px; border-radius: 99px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; white-space:nowrap;">
                            {{ $pStatus[2] }}
                        </span>
                    </td>
                    <td>
                        @php
                        $bStatus = [
                        'pending' => ['var(--warning-bg)', 'var(--warning-text)', 'Pending'],
                        'confirmed' => ['var(--info-bg)', 'var(--info-text)', 'Confirmed'],
                        'ongoing' => ['rgba(139, 92, 246, 0.1)', '#8B5CF6', 'Active'],
                        'completed' => ['var(--success-bg)', 'var(--success-text)', 'Completed'],
                        'cancelled' => ['var(--danger-bg)', 'var(--danger-text)', 'Cancelled'],
                        'expired' => ['var(--danger-bg)', 'var(--danger-text)', 'Expired'],
                        ][$booking->booking_status] ?? ['rgba(0,0,0,0.05)', 'var(--text-muted)', $booking->booking_status];
                        @endphp
                        <span style="--bg: {{ $bStatus[0] }}; --color: {{ $bStatus[1] }}; background: var(--bg); color: var(--color); padding: 4px 12px; border-radius: 99px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; white-space:nowrap;">
                            {{ $bStatus[2] }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; justify-content: flex-end;">
                            <a href="javascript:void(0)" onclick="openBookingDetail('{{ $booking->id }}')" class="action-btn-circle">
                                <i class="fas fa-eye" style="font-size: 12px;"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">
        {{ $bookings->links() }}
    </div>
</div>

<!-- BOOKING DETAIL MODAL -->
<div class="modal-overlay" id="bookingModal">
    <div class="modal-content">
        <div class="modal-header">
            <div>
                <h2 class="page-title" style="font-size: 20px;">Intelligence Report</h2>
                <div class="breadcrumb">
                    <span>Booking Code: </span>
                    <span id="det-booking-code" style="font-family: monospace; font-weight: bold; color: var(--secondary); font-size: 14px; margin-left: 4px;">-</span>
                </div>
            </div>
            <button class="action-btn-circle" onclick="closeModal('bookingModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <div class="detail-grid">
                <!-- Left Column -->
                <div>
                    <div class="info-section">
                        <h3 class="section-title"><i class="fas fa-user-shield"></i> Entity Verification</h3>
                        <div class="data-row">
                            <span class="data-label">Customer Name</span>
                            <span class="data-value" id="det-name">-</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Primary Contact</span>
                            <span class="data-value" id="det-phone">-</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Allocated Asset</span>
                            <span class="data-value" style="color: var(--secondary);" id="det-car">-</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Rental Type</span>
                            <span class="data-value" id="det-rental-type">-</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Service Option</span>
                            <span class="data-value" id="det-service-option">-</span>
                        </div>
                    </div>

                    <div class="info-section">
                        <h3 class="section-title"><i class="fas fa-map-location-dot"></i> Service Logistics</h3>
                        <div class="data-row">
                            <span class="data-label">Duration</span>
                            <span class="data-value" id="det-duration">-</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Departure</span>
                            <span class="data-value" id="det-pickup">-</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Arrival</span>
                            <span class="data-value" id="det-return">-</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Pickup Location</span>
                            <span class="data-value" id="det-pickup-location">-</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Return Location</span>
                            <span class="data-value" id="det-return-location">-</span>
                        </div>
                    </div>

                    <div class="info-section" style="border: 1px solid var(--secondary); background: rgba(212, 175, 55, 0.05);">
                        <h3 class="section-title"><i class="fas fa-wallet"></i> Financial Details</h3>
                        <div class="data-row">
                            <span class="data-label">Base Rate</span>
                            <span class="data-value" id="det-base-rate">-</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Driver Fee</span>
                            <span class="data-value" id="det-driver-fee">-</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Delivery/Extra Fee</span>
                            <span class="data-value" id="det-delivery-fee">-</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Out of Town Surcharge</span>
                            <span class="data-value" id="det-extra-price">-</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Discount</span>
                            <span class="data-value" style="color: var(--danger);" id="det-discount">-</span>
                        </div>
                        <div class="data-row">
                            <span class="data-label">Tax (PPN)</span>
                            <span class="data-value" id="det-tax">-</span>
                        </div>
                        <div class="data-row" id="row-late-fee" style="display: none;">
                            <span class="data-label">Late Fee</span>
                            <span class="data-value" style="color: var(--danger);" id="det-late-fee">-</span>
                        </div>
                        <div style="margin-top: 16px; padding-top: 16px; border-top: 1px dashed var(--card-border); display: flex; justify-content: space-between; align-items: flex-end;">
                            <div>
                                <div class="data-label" style="margin-bottom: 4px;">Total Assessment</div>
                                <div class="page-title" style="font-size: 24px;" id="det-grand-total">-</div>
                            </div>
                            <span class="booking-code-chip" id="det-payment-status">-</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <div class="info-section">
                        <h3 class="section-title"><i class="fas fa-id-card"></i> Identity Assets</h3>
                        <div id="det-documents-container" class="d-flex flex-column gap-3">
                            <!-- Dynamic document cards -->
                        </div>
                    </div>

                    <div class="info-section">
                        <h3 class="section-title"><i class="fas fa-clock-rotate-left"></i> Cycle Status</h3>
                        <div class="timeline" id="det-timeline">
                            <!-- Dynamic timeline -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-header" style="border-top: 1px solid var(--card-border); border-bottom: none; background: rgba(0,0,0,0.02);">
            <button id="btn-cancel-booking" class="btn-premium-glass" style="color: var(--danger); border-color: rgba(239, 68, 68, 0.2);">
                <i class="fas fa-ban"></i> Cancel Reservation
            </button>
            <div style="display: flex; gap: 12px;">
                <a id="btn-export-pdf" href="#" target="_blank" class="btn-premium-glass" style="text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <button id="btn-approve-booking" class="btn-premium-gold">Approve & Issue Invoice</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentBookingId = null;

    function toggleAdvancedFilter() {
        const panel = document.getElementById('advancedFilterPanel');
        panel.classList.toggle('show');
    }

    function formatRupiah(number) {
        if (number === null || number === undefined) return 'Rp 0';
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number);
    }

    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }) + ' WIB';
    }

    function openBookingDetail(id) {
        currentBookingId = id;
        
        // Reset modal to loading state
        document.getElementById('det-booking-code').innerText = 'Memuat...';
        document.getElementById('det-name').innerText = 'Memuat...';
        document.getElementById('det-phone').innerText = 'Memuat...';
        document.getElementById('det-car').innerText = 'Memuat...';
        document.getElementById('det-rental-type').innerText = 'Memuat...';
        document.getElementById('det-service-option').innerText = 'Memuat...';
        document.getElementById('det-duration').innerText = 'Memuat...';
        document.getElementById('det-pickup').innerText = 'Memuat...';
        document.getElementById('det-return').innerText = 'Memuat...';
        document.getElementById('det-pickup-location').innerText = 'Memuat...';
        document.getElementById('det-return-location').innerText = 'Memuat...';
        
        document.getElementById('det-base-rate').innerText = 'Memuat...';
        document.getElementById('det-driver-fee').innerText = 'Memuat...';
        document.getElementById('det-delivery-fee').innerText = 'Memuat...';
        document.getElementById('det-extra-price').innerText = 'Memuat...';
        document.getElementById('det-discount').innerText = 'Memuat...';
        document.getElementById('det-tax').innerText = 'Memuat...';
        document.getElementById('row-late-fee').style.display = 'none';
        
        document.getElementById('det-grand-total').innerText = 'Memuat...';
        document.getElementById('det-payment-status').innerText = 'LOADING';
        document.getElementById('det-documents-container').innerHTML = '<div class="text-center text-muted py-3 small">Memuat berkas dokumen...</div>';
        document.getElementById('det-timeline').innerHTML = '<div class="text-center text-muted py-3 small">Memuat linimasa...</div>';
        
        // Hide buttons until loaded
        document.getElementById('btn-approve-booking').style.display = 'none';
        document.getElementById('btn-cancel-booking').style.display = 'none';
        document.getElementById('btn-export-pdf').style.display = 'none';

        // Open modal window
        document.getElementById('bookingModal').classList.add('show');
        document.body.style.overflow = 'hidden';

        // Fetch data via API
        fetch(`/dashboard/bookings/${id}`)
            .then(res => {
                if (!res.ok) throw new Error('Gagal mengambil data');
                return res.json();
            })
            .then(booking => {
                const customer = booking.customer || {};
                
                // Populate text fields
                document.getElementById('det-booking-code').innerText = booking.booking_code || '-';
                document.getElementById('det-name').innerText = customer.name || booking.guest_name || '-';
                document.getElementById('det-phone').innerText = customer.phone || booking.guest_phone || '-';
                document.getElementById('det-car').innerText = booking.car 
                    ? `${booking.car.car_name} (${booking.car.plate_number})` 
                    : '-';
                document.getElementById('det-rental-type').innerText = booking.rental_type === 'monthly' ? 'Bulanan (Monthly)' : 'Harian (Daily)';
                document.getElementById('det-service-option').innerText = booking.with_driver ? 'Dengan Driver (With Driver)' : 'Lepas Kunci (Self Drive)';
                document.getElementById('det-duration').innerText = (booking.total_day || 1) + ' Hari (Days)';
                document.getElementById('det-pickup').innerText = formatDate(booking.pickup_date);
                document.getElementById('det-return').innerText = formatDate(booking.return_date);
                document.getElementById('det-pickup-location').innerText = booking.pickup_location || 'Cabang Utama / Store Office';
                document.getElementById('det-return-location').innerText = booking.return_location || 'Cabang Utama / Store Office';
                
                // Populate financial details
                document.getElementById('det-base-rate').innerText = formatRupiah(booking.price);
                document.getElementById('det-driver-fee').innerText = formatRupiah(booking.driver_price);
                
                const deliveryFees = (parseFloat(booking.delivery_fee) || 0) + 
                                     (parseFloat(booking.pickup_fee) || 0) + 
                                     (parseFloat(booking.ojol_fee) || 0);
                document.getElementById('det-delivery-fee').innerText = formatRupiah(deliveryFees);
                document.getElementById('det-extra-price').innerText = formatRupiah(booking.extra_price);
                document.getElementById('det-discount').innerText = formatRupiah(booking.discount);
                document.getElementById('det-tax').innerText = formatRupiah(booking.tax);
                
                const rowLateFee = document.getElementById('row-late-fee');
                if (parseFloat(booking.late_fee) > 0) {
                    document.getElementById('det-late-fee').innerText = formatRupiah(booking.late_fee);
                    rowLateFee.style.display = 'flex';
                } else {
                    rowLateFee.style.display = 'none';
                }
                
                document.getElementById('det-grand-total').innerText = formatRupiah(booking.grand_total);
                
                // Financial Status Chip
                const payStatus = document.getElementById('det-payment-status');
                payStatus.innerText = (booking.payment_status || 'UNPAID').toUpperCase();
                
                // Populate document assets
                const docsContainer = document.getElementById('det-documents-container');
                docsContainer.innerHTML = '';
                let hasDocs = false;
                
                const documentList = [
                    { label: 'Foto Selfie Penyewa', path: customer.selfie_image || booking.selfie_path },
                    { label: 'Foto KTP', path: customer.ktp_image || booking.ktp_path },
                    { label: 'Foto SIM', path: customer.sim_image || booking.sim_path },
                    { label: 'Foto Kartu Keluarga', path: customer.kk_photo },
                    { label: 'Foto ID Card / NIP / NIM', path: customer.id_card_photo }
                ];
                
                documentList.forEach(doc => {
                    if (doc.path) {
                        hasDocs = true;
                        const fileUrl = `/storage/${doc.path}`;
                        docsContainer.innerHTML += `
                            <a href="${fileUrl}" target="_blank" class="doc-card" style="text-decoration: none; display: flex; flex-direction: row; gap: 12px; padding: 12px 20px; align-items: center; justify-content: flex-start; height: auto; aspect-ratio: auto;">
                                <i class="fas fa-file-image" style="font-size: 20px; margin-bottom: 0; color: var(--secondary); flex-shrink: 0;"></i>
                                <div style="text-align: left;">
                                    <div style="font-size: 11px; font-weight: 800; color: var(--text-main); line-height: 1.2;">${doc.label}</div>
                                    <div style="font-size: 9px; color: var(--text-muted); font-weight: 600; margin-top: 2px;">Klik untuk memperbesar / unduh</div>
                                </div>
                            </a>
                        `;
                    }
                });
                
                if (!hasDocs) {
                    docsContainer.innerHTML = `
                        <div style="text-align: center; padding: 20px; border: 2px dashed var(--card-border); border-radius: 20px; color: var(--text-muted); font-size: 11px; font-weight: 600;">
                            <i class="fas fa-folder-open" style="font-size: 24px; margin-bottom: 8px; opacity: 0.5; display: block; margin-left: auto; margin-right: auto;"></i>
                            Tidak ada berkas dokumen diunggah
                        </div>
                    `;
                }

                // Populate Timeline
                const timeline = document.getElementById('det-timeline');
                timeline.innerHTML = '';
                const bStatus = booking.booking_status;

                // Step 1: Booking Received (selalu done)
                timeline.innerHTML += `
                    <div class="timeline-item done">
                        <div class="data-value" style="font-size: 12px;">Booking Received</div>
                        <div class="data-label" style="font-size: 10px;">Pesanan telah dicatat di database</div>
                    </div>
                `;

                // Step 2: Payment & Verification
                let step2Class = 'not-started';
                let step2Label = 'Menunggu verifikasi pembayaran / persetujuan';
                if (bStatus === 'pending') {
                    step2Class = 'active';
                } else if (['confirmed', 'ongoing', 'completed'].includes(bStatus)) {
                    step2Class = 'done';
                    step2Label = booking.payment_status === 'paid' ? 'Lunas & Diverifikasi' : 'Disetujui Admin';
                } else if (bStatus === 'cancelled') {
                    step2Class = 'cancelled';
                    step2Label = 'Pesanan Dibatalkan';
                }
                timeline.innerHTML += `
                    <div class="timeline-item ${step2Class}">
                        <div class="data-value" style="font-size: 12px;">Payment & Verification</div>
                        <div class="data-label" style="font-size: 10px;">${step2Label}</div>
                    </div>
                `;

                // Step 3: Car Logistics & Cycle
                let step3Class = 'not-started';
                let step3Label = 'Belum dimulai';
                if (bStatus === 'confirmed') {
                    step3Class = 'active';
                    step3Label = 'Armada Siap / Dijadwalkan';
                } else if (bStatus === 'ongoing') {
                    step3Class = 'done';
                    step3Label = 'Rental Sedang Aktif (Armada di Jalan)';
                } else if (bStatus === 'completed') {
                    step3Class = 'done';
                    step3Label = 'Rental Selesai & Dikembalikan';
                } else if (bStatus === 'cancelled') {
                    step3Class = 'cancelled';
                    step3Label = 'Logistik Dibatalkan';
                }
                timeline.innerHTML += `
                    <div class="timeline-item ${step3Class}">
                        <div class="data-value" style="font-size: 12px;">Car Logistics & Cycle</div>
                        <div class="data-label" style="font-size: 10px;">${step3Label}</div>
                    </div>
                `;

                // Set PDF Export Link
                const exportBtn = document.getElementById('btn-export-pdf');
                exportBtn.href = `/invoice/${booking.booking_code}?download=1`;
                exportBtn.style.display = 'flex';

                // Handle Actions Button States
                const approveBtn = document.getElementById('btn-approve-booking');
                const cancelBtn = document.getElementById('btn-cancel-booking');

                if (bStatus === 'pending') {
                    approveBtn.style.display = 'block';
                    cancelBtn.style.display = 'block';
                } else if (bStatus === 'confirmed') {
                    approveBtn.style.display = 'none';
                    cancelBtn.style.display = 'block';
                } else {
                    approveBtn.style.display = 'none';
                    cancelBtn.style.display = 'none';
                }
            })
            .catch(err => {
                alert('Gagal mengambil detail pesanan: ' + err.message);
                closeModal('bookingModal');
            });
    }

    // Approve booking action
    document.getElementById('btn-approve-booking').addEventListener('click', function() {
        if (!currentBookingId) return;

        if (confirm('Apakah Anda yakin ingin menyetujui (approve) booking ini?')) {
            fetch(`/dashboard/bookings/${currentBookingId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Gagal menyetujui pesanan: ' + (data.message || 'Error tidak diketahui'));
                }
            })
            .catch(err => {
                alert('Terjadi kesalahan jaringan: ' + err.message);
            });
        }
    });

    // Cancel booking action
    document.getElementById('btn-cancel-booking').addEventListener('click', function() {
        if (!currentBookingId) return;

        if (confirm('Apakah Anda yakin ingin membatalkan (cancel) booking ini?')) {
            fetch(`/dashboard/bookings/${currentBookingId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Gagal membatalkan pesanan: ' + (data.message || 'Error tidak diketahui'));
                }
            })
            .catch(err => {
                alert('Terjadi kesalahan jaringan: ' + err.message);
            });
        }
    });

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal('bookingModal');
    });

    document.getElementById('bookingModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal('bookingModal');
    });

    // ==========================================
    // ANALYTICS INTELLIGENCE CHART SETUP
    // ==========================================
    let revenueChartInstance = null;
    let CarShareChartInstance = null;

    function initCharts(theme = 'light') {
        const textColor = theme === 'dark' ? '#F1F5F9' : '#1E293B';
        const mutedColor = theme === 'dark' ? '#94A3B8' : '#64748B';
        const gridColor = theme === 'dark' ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.04)';
        
        // Revenue & Rental Chart (Line & Bar Combined)
        const ctxRev = document.getElementById('revenueChart');
        if (ctxRev) {
            const ctx = ctxRev.getContext('2d');
            if (revenueChartInstance) revenueChartInstance.destroy();
            
            revenueChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan 2026', 'Feb 2026', 'Mar 2026', 'Apr 2026', 'Mei 2026'],
                    datasets: [
                        {
                            type: 'line',
                            label: 'Monthly Revenue',
                            data: [12500000, 18500000, 15000000, 29000000, 42720000],
                            borderColor: '#D4AF37',
                            borderWidth: 3,
                            pointBackgroundColor: theme === 'dark' ? '#0F172A' : '#FFFFFF',
                            pointBorderColor: '#D4AF37',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            tension: 0.4,
                            fill: false,
                            yAxisID: 'y'
                        },
                        {
                            type: 'bar',
                            label: 'Total Bookings',
                            data: [8, 12, 10, 18, 25],
                            backgroundColor: 'rgba(212, 175, 55, 0.15)',
                            borderColor: 'rgba(212, 175, 55, 0.4)',
                            borderWidth: 1,
                            borderRadius: 6,
                            barPercentage: 0.4,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: textColor,
                                font: { family: 'Poppins', size: 11, weight: 600 }
                            }
                        },
                        tooltip: {
                            backgroundColor: theme === 'dark' ? '#0F172A' : '#FFFFFF',
                            titleColor: textColor,
                            bodyColor: textColor,
                            borderColor: '#D4AF37',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.datasetIndex === 0) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.raw);
                                    } else {
                                        label += context.raw + ' Rentals';
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: gridColor },
                            ticks: {
                                color: mutedColor,
                                font: { family: 'Inter', size: 10, weight: 600 }
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: { color: gridColor },
                            ticks: {
                                color: mutedColor,
                                font: { family: 'Inter', size: 10, weight: 600 },
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000) + ' Jt';
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: {
                                color: mutedColor,
                                font: { family: 'Inter', size: 10, weight: 600 }
                            }
                        }
                    }
                }
            });
        }

        // Car Category Share Chart (Doughnut)
        const ctxCar = document.getElementById('CarShareChart');
        if (ctxCar) {
            const ctx = ctxCar.getContext('2d');
            if (CarShareChartInstance) CarShareChartInstance.destroy();
            
            CarShareChartInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Mercedes S-Class', 'Toyota Rush', 'Land Cruiser', 'Toyota Innova'],
                    datasets: [{
                        data: [70, 10, 10, 10],
                        backgroundColor: [
                            '#D4AF37',
                            'rgba(212, 175, 55, 0.7)',
                            'rgba(212, 175, 55, 0.5)',
                            'rgba(212, 175, 55, 0.2)'
                        ],
                        borderColor: theme === 'dark' ? '#0F172A' : '#FFFFFF',
                        borderWidth: 3,
                        hoverOffset: 12
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: textColor,
                                font: { family: 'Inter', size: 10, weight: 600 },
                                padding: 12
                            }
                        },
                        tooltip: {
                            backgroundColor: theme === 'dark' ? '#0F172A' : '#FFFFFF',
                            titleColor: textColor,
                            bodyColor: textColor,
                            borderColor: '#D4AF37',
                            borderWidth: 1,
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.label + ': ' + context.raw + '% Demand Share';
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Dynamic theme updater hook called by layouts theme switcher
    window.updateChartsTheme = function(theme) {
        initCharts(theme);
    };

    // Auto load on startup
    document.addEventListener('DOMContentLoaded', () => {
        const initialTheme = document.documentElement.getAttribute('data-theme') || 'light';
        initCharts(initialTheme);
    });
</script>
@endsection