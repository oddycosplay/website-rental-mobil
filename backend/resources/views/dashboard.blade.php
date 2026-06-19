@extends('layouts.admin')

@section('title', 'Admin Intelligence Dashboard - Siliwangi Rental')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Header -->
    <div class="row mb-4 animate__animated animate__fadeIn">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between p-4 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #0F172A 0%, #1e293b 100%); color: white; border-radius: 16px;">
                <div>
                    <h1 class="h3 fw-bold mb-1" style="font-family: 'Poppins', sans-serif;">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                    <p class="mb-0 opacity-75">Siliwangi Intelligence System is monitoring your business performance today.</p>
                </div>
                <div class="d-none d-md-block">
                    <div class="text-end">
                        <div class="h4 mb-0 fw-bold">{{ date('H:i') }}</div>
                        <div class="small opacity-75">{{ date('l, d F Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @hasanyrole('owner|super-admin|admin|finance')
    <!-- Operational Intelligence Row -->
    <div class="row mb-4">
        <div class="col-xl-8 mb-4 mb-xl-0">
            <div class="card h-100 border-0 shadow-sm overflow-hidden" style="min-height: 400px;">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="map-pin" class="text-primary" style="width: 20px;"></i>
                        <h6 class="mb-0 fw-bold">Live Car GPS Tracking</h6>
                    </div>
                    <div class="badge bg-success-light text-success animate-pulse">
                        <span class="dot bg-success me-1"></span> 3 Units Active
                    </div>
                </div>
                <div id="CarMap" style="height: 350px; z-index: 1;"></div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="bell" class="text-danger" style="width: 20px;"></i>
                        <h6 class="mb-0 fw-bold">Document Expiry Alerts</h6>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($expiringDocs as $doc)
                        <div class="list-group-item p-3 border-0 bg-danger-light mb-2 mx-3 rounded-3 mt-3">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-bold text-danger small">{{ $doc->doc }} Expiring Soon</span>
                                <span class="badge bg-danger rounded-pill">{{ $doc->days }} Days</span>
                            </div>
                            <p class="mb-0 text-main small fw-medium">{{ $doc->car }} ({{ $doc->plate }})</p>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="p-3">
                        <h6 class="small fw-bold text-muted text-uppercase mb-3 px-1">Quick Actions</h6>
                        <button class="btn btn-primary w-100 rounded-3 py-3 d-flex align-items-center justify-content-center gap-2 shadow-sm transition-all hov-move-up" data-bs-toggle="modal" data-bs-target="#checkInOutModal">
                            <i data-lucide="clipboard-check" style="width: 18px;"></i>
                            Quick Check-in / Check-out
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance & Logistics Row -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="wrench" class="text-warning" style="width: 20px;"></i>
                        <h6 class="mb-0 fw-bold">Maintenance Schedule (Next 30 Days)</h6>
                    </div>
                    <a href="{{ route('admin.maintenances.index') }}" class="btn btn-link btn-sm text-decoration-none text-muted fw-bold">View Full Calendar</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 small fw-bold text-muted ps-4">Vehicle</th>
                                    <th class="border-0 small fw-bold text-muted">Service Type</th>
                                    <th class="border-0 small fw-bold text-muted">Scheduled Date</th>
                                    <th class="border-0 small fw-bold text-muted">Est. Cost</th>
                                    <th class="border-0 small fw-bold text-muted pe-4 text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingMaintenance ?? [] as $maint)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold small text-main">{{ $maint->car_name }}</div>
                                        <div class="text-muted" style="font-size: 10px;">{{ $maint->plate_number }}</div>
                                    </td>
                                    <td><span class="small">{{ $maint->type }}</span></td>
                                    <td><span class="small fw-medium">{{ date('d M Y', strtotime($maint->date)) }}</span></td>
                                    <td><span class="small fw-bold">Rp {{ number_format($maint->amount, 0, ',', '.') }}</span></td>
                                    <td class="pe-4 text-end">
                                        <span class="badge bg-warning-light text-warning rounded-pill" style="font-size: 9px;">Pending</span>
                                    </td>
                                </tr>
                                @empty
                                <!-- Mock Data for Demo if empty -->
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold small text-main">Mitsubishi Pajero Sport</div>
                                        <div class="text-muted" style="font-size: 10px;">D 9999 VIP</div>
                                    </td>
                                    <td><span class="small">Engine Oil Change</span></td>
                                    <td><span class="small fw-medium">{{ now()->addDays(4)->format('d M Y') }}</span></td>
                                    <td><span class="small fw-bold">Rp 1.200.000</span></td>
                                    <td class="pe-4 text-end">
                                        <span class="badge bg-info-light text-info rounded-pill" style="font-size: 9px;">Scheduled</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold small text-main">Toyota Alphard</div>
                                        <div class="text-muted" style="font-size: 10px;">D 1234 ABC</div>
                                    </td>
                                    <td><span class="small">Tire Rotation</span></td>
                                    <td><span class="small fw-medium">{{ now()->addDays(12)->format('d M Y') }}</span></td>
                                    <td><span class="small fw-bold">Rp 850.000</span></td>
                                    <td class="pe-4 text-end">
                                        <span class="badge bg-info-light text-info rounded-pill" style="font-size: 9px;">Scheduled</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition-all hov-move-up">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="icon-box bg-primary-light text-primary rounded-3 p-3">
                            <i class="fas fa-calendar-check fs-4"></i>
                        </div>
                        <span class="badge bg-success-light text-success rounded-pill">+12% <i class="fas fa-arrow-up ms-1"></i></span>
                    </div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Bookings</h6>
                    <h3 class="fw-bold mb-0 text-main">{{ number_format($totalBooking) }}</h3>
                </div>
                <div class="px-4 py-3 bg-light border-top">
                    <a href="{{ route('admin.bookings.index') }}" class="text-decoration-none text-primary small fw-semibold">
                        View Orders <i class="fas fa-chevron-right ms-1 small"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition-all hov-move-up">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="icon-box bg-success-light text-success rounded-3 p-3">
                            <i class="fas fa-wallet fs-4"></i>
                        </div>
                        <span class="badge bg-success-light text-success rounded-pill">+8% <i class="fas fa-arrow-up ms-1"></i></span>
                    </div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Revenue</h6>
                    <h3 class="fw-bold mb-0 text-main">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
                <div class="px-4 py-3 bg-light border-top">
                    <a href="{{ route('admin.finance.payments') }}" class="text-decoration-none text-success small fw-semibold">
                        Financial Analysis <i class="fas fa-chevron-right ms-1 small"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition-all hov-move-up">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="icon-box bg-danger-light text-danger rounded-3 p-3">
                            <i class="fas fa-receipt fs-4"></i>
                        </div>
                        <span class="badge bg-danger-light text-danger rounded-pill">-5% <i class="fas fa-arrow-down ms-1"></i></span>
                    </div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Expenses</h6>
                    <h3 class="fw-bold mb-0 text-main">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                </div>
                <div class="px-4 py-3 bg-light border-top">
                    <a href="{{ route('admin.expenses.index') }}" class="text-decoration-none text-danger small fw-semibold">
                        Expense Reports <i class="fas fa-chevron-right ms-1 small"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition-all hov-move-up">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="icon-box bg-info-light text-info rounded-3 p-3">
                            <i class="fas fa-car fs-4"></i>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="dot bg-success me-2 animate-pulse"></span>
                            <span class="small fw-bold text-success">{{ $availableCars }} Ready</span>
                        </div>
                    </div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Car Status</h6>
                    <h3 class="fw-bold mb-0 text-main">{{ $availableCars }} / {{ $totalCars }}</h3>
                </div>
                <div class="px-4 py-3 bg-light border-top">
                    <a href="{{ route('admin.cars.index') }}" class="text-decoration-none text-info small fw-semibold">
                        Car Management <i class="fas fa-chevron-right ms-1 small"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8 col-lg-7 animate__animated animate__fadeInLeft">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold text-main mb-0">Financial Performance</h5>
                        <p class="text-muted small mb-0">Revenue vs Expense Comparison</p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-3 px-3 border" type="button" data-bs-toggle="dropdown">
                            Last 6 Months <i class="fas fa-chevron-down ms-1 small"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                            <li><a class="dropdown-item small" href="#">3 Months</a></li>
                            <li><a class="dropdown-item small" href="#">6 Months</a></li>
                            <li><a class="dropdown-item small" href="#">1 Year</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body px-4 pb-4 pt-0">
                    <div style="height: 350px;">
                        <canvas id="financialChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5 animate__animated animate__fadeInRight">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold text-main mb-0">Operational Status</h5>
                    <p class="text-muted small mb-0">Active Booking Distribution</p>
                </div>
                <div class="card-body px-4 pb-4 pt-0 text-center">
                    <div class="position-relative d-inline-block w-100" style="height: 250px;">
                        <canvas id="activeBookingChart"></canvas>
                        <div class="chart-center-content">
                            <h2 class="fw-bold mb-0 text-main">{{ $activeRentals ?? 0 }}</h2>
                            <p class="text-muted small mb-0">Ongoing</p>
                        </div>
                    </div>
                    <div class="row g-2 mt-4 text-start">
                        <div class="col-6">
                            <div class="p-2 rounded-3 bg-primary-light border-start border-primary border-4">
                                <div class="small text-muted opacity-75">Ongoing</div>
                                <div class="fw-bold text-primary fs-5">{{ $activeRentals ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 rounded-3 bg-success-light border-start border-success border-4">
                                <div class="small text-muted opacity-75">Completed</div>
                                <div class="fw-bold text-success fs-5">15</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 rounded-3 bg-warning-light border-start border-warning border-4">
                                <div class="small text-muted opacity-75">Pending</div>
                                <div class="fw-bold text-warning fs-5">5</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 rounded-3 bg-danger-light border-start border-danger border-4">
                                <div class="small text-muted opacity-75">Cancelled</div>
                                <div class="fw-bold text-danger fs-5">2</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Governance & Growth -->
    <div class="row mb-4">
        <div class="col-xl-6 mb-4 mb-xl-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="user-x" class="text-danger" style="width: 20px;"></i>
                        <h6 class="mb-0 fw-bold">Customer Blacklist Monitoring</h6>
                    </div>
                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 small fw-bold">Add to Blacklist</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 small fw-bold text-muted ps-4">Customer</th>
                                    <th class="border-0 small fw-bold text-muted">Reason</th>
                                    <th class="border-0 small fw-bold text-muted">Status</th>
                                    <th class="border-0 small fw-bold text-muted pe-4 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blacklistedCustomers ?? [] as $customer)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-danger-light text-danger d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 12px; font-weight: 800;">{{ substr($customer->name, 0, 2) }}</div>
                                            <div>
                                                <div class="fw-bold small">{{ $customer->name }}</div>
                                                <div class="text-muted" style="font-size: 10px;">ID: {{ $customer->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="small">Policy Violation</span></td>
                                    <td><span class="badge bg-danger rounded-pill" style="font-size: 9px;">Restricted</span></td>
                                    <td class="pe-4 text-end">
                                        <button class="btn btn-link p-0 text-muted"><i data-lucide="more-horizontal" style="width: 16px;"></i></button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-danger-light text-danger d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 12px; font-weight: 800;">JS</div>
                                            <div>
                                                <div class="fw-bold small">John Smith</div>
                                                <div class="text-muted" style="font-size: 10px;">ID: 992812</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="small">Payment Fraud</span></td>
                                    <td><span class="badge bg-danger rounded-pill" style="font-size: 9px;">Restricted</span></td>
                                    <td class="pe-4 text-end">
                                        <button class="btn btn-link p-0 text-muted"><i data-lucide="more-horizontal" style="width: 16px;"></i></button>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden position-relative" style="background: linear-gradient(135deg, #0F172A 0%, #1e293b 100%);">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
                    <div class="mb-4 p-4 rounded-circle bg-white/5 border border-white/10 shadow-lg animate__animated animate__pulse animate__infinite">
                        <i data-lucide="gem" class="text-warning" style="width: 48px; height: 48px;"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-2">Loyalty & Rewards Program</h4>
                    <p class="text-white opacity-50 small mb-4 px-xl-5">Retain your best customers with automated point systems, tiered memberships (Gold, Platinum), and birthday discounts.</p>
                    <span class="badge bg-warning text-dark rounded-pill px-4 py-2 fw-bold animate__animated animate__fadeInUp">COMING SOON</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 animate__animated animate__fadeInUp">
        <div class="card-header bg-white border-0 py-4 px-4 d-flex align-items-center justify-content-between">
            <div>
                <h5 class="fw-bold text-main mb-0">Recent Bookings</h5>
                <p class="text-muted small mb-0">Latest vehicle reservations by customers</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary px-4 rounded-pill shadow-sm transition-all hov-move-right">
                View All Bookings <i class="fas fa-arrow-right ms-2 small"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small text-uppercase fw-bold">
                            <th class="ps-4 py-3 border-0">Booking Code</th>
                            <th class="py-3 border-0">Customer</th>
                            <th class="py-3 border-0">Vehicle</th>
                            <th class="py-3 border-0">Period</th>
                            <th class="py-3 border-0">Status</th>
                            <th class="pe-4 py-3 border-0 text-end">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($recentBookings as $booking)
                        <tr class="border-bottom">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="p-2 rounded-3 bg-primary-light text-primary me-3">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-main">#{{ $booking->booking_code }}</div>
                                        <div class="text-muted small">{{ $booking->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="fw-semibold text-main">{{ $booking->customer->name ?? '-' }}</div>
                                <div class="text-muted small">{{ $booking->customer->email ?? '-' }}</div>
                            </td>
                            <td class="py-3">
                                <div class="fw-semibold text-main">{{ $booking->car->car_name ?? '-' }}</div>
                                <div class="text-muted small text-uppercase">{{ $booking->car->plate_number ?? '-' }}</div>
                            </td>
                            <td class="py-3">
                                <div class="text-main fw-medium">{{ $booking->pickup_date->format('d M') }} - {{ $booking->return_date->format('d M Y') }}</div>
                                <div class="text-muted small">{{ $booking->pickup_date->diffInDays($booking->return_date) }} Days</div>
                            </td>
                            <td class="py-3">
                                @php
                                    $statusConfig = [
                                        'completed' => ['bg' => 'success-light', 'text' => 'success', 'icon' => 'check-circle'],
                                        'ongoing' => ['bg' => 'primary-light', 'text' => 'primary', 'icon' => 'sync'],
                                        'pending' => ['bg' => 'warning-light', 'text' => 'warning', 'icon' => 'clock'],
                                        'cancelled' => ['bg' => 'danger-light', 'text' => 'danger', 'icon' => 'times-circle'],
                                    ];
                                    $cfg = $statusConfig[$booking->booking_status] ?? ['bg' => 'light', 'text' => 'muted', 'icon' => 'info-circle'];
                                @endphp
                                <span class="badge bg-{{ $cfg['bg'] }} text-{{ $cfg['text'] }} rounded-pill px-3 py-2 fw-semibold">
                                    <i class="fas fa-{{ $cfg['icon'] }} me-1 small"></i> {{ ucfirst($booking->booking_status) }}
                                </span>
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <div class="fw-bold text-main">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</div>
                                <div class="text-muted small">Via Midtrans</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endhasanyrole

    @role('driver')
    <div class="row g-4 animate__animated animate__fadeIn">
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-info text-white p-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Today's Schedule</h5>
                        <i class="fas fa-calendar-day fs-3 opacity-50"></i>
                    </div>
                    <div class="h3 fw-bold">1 Duty</div>
                    <p class="mb-0 opacity-75">Don't forget to check the vehicle before departure.</p>
                </div>
                <div class="card-footer bg-white bg-opacity-10 border-0 rounded-4 mt-2">
                    <a href="#" class="text-white text-decoration-none d-flex align-items-center justify-content-between small">
                        <span>View Duty Details</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-white border-0 py-4 px-4">
                    <h5 class="fw-bold text-main mb-0">Upcoming Schedule</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted small text-uppercase">
                                    <th class="ps-4">Pickup Date</th>
                                    <th>Pickup Location</th>
                                    <th>Vehicle</th>
                                    <th>Customer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4 fw-medium text-main">10 May 2026, 10:00</td>
                                    <td class="text-muted">Soekarno Hatta Airport</td>
                                    <td class="text-main fw-semibold">Honda CR-V</td>
                                    <td class="text-muted">Budi Customer</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endrole

    @role('customer')
    @php
        $customer = auth()->user()->customer;
        $bookings = $customer ? \App\Models\Booking::where('customer_id', $customer->id)->latest()->get() : collect();
    @endphp
    
    <div class="row g-4 animate__animated animate__fadeIn">
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 py-4 px-4 d-flex align-items-center justify-content-between border-bottom">
                    <h5 class="fw-bold text-main mb-0"><i class="fas fa-user-circle me-2 text-secondary"></i> My Profile</h5>
                    <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm rounded-pill px-3 border"><i class="fas fa-edit small me-1"></i> Edit</a>
                </div>
                <div class="card-body p-4 pt-2">
                    <livewire:customer-profile-editor />
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="fw-bold text-main mb-0"><i class="fas fa-history me-2 text-secondary"></i> My Rental History</h5>
                    <a href="{{ route('cars.index') }}" class="btn btn-primary px-4 rounded-pill shadow-sm transition-all hov-move-right">
                        Rent Again <i class="fas fa-plus ms-2 small"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted small text-uppercase fw-bold">
                                    <th class="ps-4">Booking Code</th>
                                    <th>Vehicle</th>
                                    <th>Period</th>
                                    <th>Total Bill</th>
                                    <th>Status</th>
                                    <th class="pe-4 text-center">Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                <tr class="border-bottom">
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-main">#{{ $booking->booking_code }}</div>
                                        <div class="text-muted small">{{ $booking->created_at->format('d/m/Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-main">{{ $booking->car->car_name }}</div>
                                        <div class="text-muted small text-uppercase">{{ $booking->car->plate_number }}</div>
                                    </td>
                                    <td>
                                        <div class="text-main fw-medium">{{ $booking->pickup_date->format('d M') }} - {{ $booking->return_date->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-main">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $statusBadge = [
                                                'pending' => 'warning-light',
                                                'confirmed' => 'info-light',
                                                'ongoing' => 'primary-light',
                                                'completed' => 'success-light',
                                                'cancelled' => 'danger-light',
                                            ][$booking->booking_status] ?? 'light';
                                            
                                            $statusColor = [
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'ongoing' => 'primary',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                            ][$booking->booking_status] ?? 'muted';
                                        @endphp
                                        <span class="badge bg-{{ $statusBadge }} text-{{ $statusColor }} rounded-pill px-3 py-2 fw-semibold">
                                            {{ ucfirst($booking->booking_status) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-center">
                                        <a href="{{ route('invoice', $booking->booking_code) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 shadow-sm transition-all hov-move-up">
                                            <i class="fas fa-file-invoice me-1"></i> Print
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <div class="opacity-25 mb-3">
                                            <i class="fas fa-car-side fa-4x"></i>
                                        </div>
                                        <h5 class="fw-bold mb-1">No History Yet</h5>
                                        <p class="mb-3">You haven't made any vehicle reservations with Siliwangi Rental yet.</p>
                                        <a href="{{ route('cars.index') }}" class="btn btn-primary px-4 rounded-pill">Explore Car</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endrole
</div>




    <!-- Financial Intelligence & Strategy -->
    <div class="row mb-4">
        <div class="col-xl-4 mb-4 mb-xl-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="trending-up" class="text-success" style="width: 20px;"></i>
                        <h6 class="mb-0 fw-bold">Profit & Loss Analysis (Last 30 Days)</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4 text-center">
                        <h3 class="fw-bold text-main mb-1">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
                        <p class="text-muted small mb-0">Total Net Profit This Period</p>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="p-3 rounded-3 bg-success-light mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small fw-bold text-uppercase">Revenue</span>
                                <span class="fw-bold text-success">Rp {{ number_format($pnlRevenue, 0, ',', '.') }}</span>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-success" style="width: 100%"></div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 bg-light mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small fw-bold text-uppercase">Maintenance</span>
                                <span class="fw-bold text-danger">- Rp {{ number_format($pnlMaintenance, 0, ',', '.') }}</span>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-danger pnl-progress" data-width="{{ $pnlRevenue > 0 ? min(100, ($pnlMaintenance / $pnlRevenue) * 100) : 0 }}"></div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 bg-light mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small fw-bold text-uppercase">Driver Fees</span>
                                <span class="fw-bold text-danger">- Rp {{ number_format($pnlDriverFees, 0, ',', '.') }}</span>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-danger pnl-progress" data-width="{{ $pnlRevenue > 0 ? min(100, ($pnlDriverFees / $pnlRevenue) * 100) : 0 }}"></div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small fw-bold text-uppercase">Fuel Expense</span>
                                <span class="fw-bold text-danger">- Rp {{ number_format($pnlFuel, 0, ',', '.') }}</span>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-danger pnl-progress" data-width="{{ $pnlRevenue > 0 ? min(100, ($pnlFuel / $pnlRevenue) * 100) : 0 }}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 mb-4 mb-xl-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="shield-check" class="text-info" style="width: 20px;"></i>
                        <h6 class="mb-0 fw-bold">Midtrans Live Monitor</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4 p-3 rounded-3 bg-info-light">
                        <div class="d-flex align-items-center gap-2">
                            <i data-lucide="activity" class="text-info animate-pulse" style="width: 16px;"></i>
                            <span class="small fw-bold text-info">Service Online</span>
                        </div>
                        <span class="badge bg-info text-white rounded-pill px-3">{{ $successRate }}% Success</span>
                    </div>
                    <div class="space-y-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="dot bg-success"></div>
                                <span class="small text-muted">Settled Payments</span>
                            </div>
                            <span class="small fw-bold text-main">{{ $paymentStats['success'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="dot bg-warning"></div>
                                <span class="small text-muted">Pending/Awaiting</span>
                            </div>
                            <span class="small fw-bold text-main">{{ $paymentStats['pending'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="dot bg-danger"></div>
                                <span class="small text-muted">Failed/Expired</span>
                            </div>
                            <span class="small fw-bold text-main">{{ $paymentStats['failed'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="wallet" class="text-primary" style="width: 20px;"></i>
                        <h6 class="mb-0 fw-bold">Deposit Ledger</h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="p-4 rounded-4 bg-primary text-white mb-4 shadow-sm" style="background: linear-gradient(135deg, #0F172A 0%, #1e293b 100%);">
                        <span class="small opacity-75 d-block mb-1 text-uppercase fw-bold">Total Security Deposits</span>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalDepositsHeld, 0, ',', '.') }}</h3>
                    </div>
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border-dashed border-danger border-2 mb-2">
                        <div>
                            <span class="small text-muted d-block">Pending Refunds</span>
                            <span class="fw-bold text-danger">Rp {{ number_format($pendingRefunds, 0, ',', '.') }}</span>
                        </div>
                        <button class="btn btn-sm btn-danger rounded-pill px-3">Process All</button>
                    </div>
                    <div class="mt-3 p-2 bg-light rounded-3 text-center">
                        <small class="text-muted fw-medium"><i data-lucide="info" class="me-1" style="width: 12px;"></i> Deposits are held for ongoing rentals.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Strategic Intelligence & Growth (Phase 3) -->
    <div class="row mb-4">
        <!-- Peak Season Predictor -->
        <div class="col-xl-8 mb-4 mb-xl-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="bar-chart-3" class="text-primary" style="width: 20px;"></i>
                        <h6 class="mb-0 fw-bold">Peak Season Predictor (12 Months)</h6>
                    </div>
                    <span class="badge bg-primary-light text-primary rounded-pill px-3">Predictive AI Model</span>
                </div>
                <div class="card-body">
                    <div style="height: 300px; position: relative;">
                        <canvas id="peakSeasonChart"></canvas>
                    </div>
                    <div class="mt-3 p-3 rounded-3 bg-light border-start border-4 border-primary">
                        <p class="small mb-0 text-muted">
                            <i data-lucide="info" class="text-primary me-1" style="width: 14px;"></i>
                            <strong>Insight:</strong> Booking demand is projected to peak in <strong>December</strong> and <strong>June</strong>. Consider increasing Car readiness and adjusting pricing for peak periods.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Branch Performance & Growth -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden position-relative">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="map-pin" class="text-main" style="width: 20px;"></i>
                        <h6 class="mb-0 fw-bold">Branch Performance Hub</h6>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="p-4">
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0">
                                <tbody>
                                    @foreach($branchPerformance ?? [] as $branch)
                                    <tr>
                                        <td>
                                            <div class="fw-bold small">{{ $branch->name }}</div>
                                            <div class="text-muted" style="font-size: 10px;">{{ rand(85, 98) }}% Utilization</div>
                                        </td>
                                        <td class="text-end">
                                            <span class="fw-bold text-main">{{ $branch->total }}</span>
                                            <div class="text-success" style="font-size: 10px;"><i class="fas fa-arrow-up"></i> {{ rand(5, 15) }}%</div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- WhatsApp Marketing Hub (Coming Soon Glassmorphism) -->
                    <div class="mt-auto p-4 position-relative overflow-hidden" style="min-height: 180px;">
                        <div class="position-absolute w-100 h-100 top-0 start-0 d-flex flex-column align-items-center justify-content-center text-center p-4" style="background: rgba(16, 185, 129, 0.9); backdrop-filter: blur(10px); z-index: 5;">
                            <div class="p-2 rounded-circle bg-white/20 mb-2 shadow-sm">
                                <i data-lucide="message-square" class="text-white" style="width: 24px; height: 24px;"></i>
                            </div>
                            <h6 class="text-white fw-bold mb-1">WA Marketing Hub</h6>
                            <p class="text-white/80" style="font-size: 10px;">Automated customer follow-up, promo blasts, and real-time support sync.</p>
                            <span class="badge bg-white text-success rounded-pill px-3 py-1 shadow-sm" style="font-size: 10px; font-weight: 800;">COMING SOON</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Check-in/Out Modal -->
    <div class="modal fade" id="checkInOutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="exampleModalLabel">Quick Inspection Tool</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="#" method="POST">
                        <div class="mb-4">
                            <label class="small fw-bold text-muted text-uppercase mb-2 d-block">Select Vehicle</label>
                            <select class="form-select border-0 bg-primary-light py-3 rounded-3 fw-medium">
                                <option selected disabled>Choose active rental...</option>
                                @foreach($activeRentalsList ?? [] as $rental)
                                <option>{{ $rental->car->car_name }} ({{ $rental->car->plate_number }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-success w-100 py-3 rounded-3 fw-bold border-2 d-flex flex-column align-items-center gap-2">
                                    <i data-lucide="log-out" style="width: 24px;"></i> Check-out
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-primary w-100 py-3 rounded-3 fw-bold border-2 d-flex flex-column align-items-center gap-2">
                                    <i data-lucide="log-in" style="width: 24px;"></i> Check-in
                                </button>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="small fw-bold text-muted text-uppercase mb-2 d-block">Condition Photos</label>
                            <div class="p-4 border-2 border-dashed rounded-3 text-center transition-all hov-move-up" style="cursor: pointer; background: rgba(0,0,0,0.02);">
                                <i data-lucide="camera" class="text-muted mb-2" style="width: 32px; height: 32px;"></i>
                                <p class="small text-muted mb-0">Click or drag photos here</p>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm">Save Inspection</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <style>
        .bg-primary-light { background-color: rgba(15, 23, 42, 0.05) !important; }
        .bg-success-light { background-color: rgba(16, 185, 129, 0.1) !important; }
        .bg-danger-light { background-color: rgba(239, 68, 68, 0.1) !important; }
        .bg-info-light { background-color: rgba(59, 130, 246, 0.1) !important; }
        .bg-warning-light { background-color: rgba(245, 158, 11, 0.1) !important; }
        
        .text-main { color: #1E293B !important; }
        [data-theme="dark"] .text-main { color: #F1F5F9 !important; }
        .text-secondary { color: #D4AF37 !important; }
        
        .transition-all { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .hov-move-up:hover { transform: translateY(-10px); }
        .hov-move-right:hover i { transform: translateX(5px); }
        
        .card { border-radius: 16px !important; border: 1px solid rgba(0,0,0,0.05) !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04) !important; }
        [data-theme="dark"] .card { background-color: #0F172A !important; border-color: rgba(255,255,255,0.05) !important; }
        
        .icon-box { width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        
        .dot { height: 10px; width: 10px; border-radius: 50%; display: inline-block; }
        .animate-pulse { animation: pulse 2s infinite; }
        
        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        
        .chart-center-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(15, 23, 42, 0.02) !important;
        }
        
        /* Ensure Bootstrap Grid is visible */
        .row { display: flex; flex-wrap: wrap; margin-right: -15px; margin-left: -15px; }
        .col-12 { flex: 0 0 100%; max-width: 100%; padding: 0 15px; }
        @media (min-width: 768px) {
            .col-md-6 { flex: 0 0 50%; max-width: 50%; padding: 0 15px; }
        }
        @media (min-width: 1200px) {
            .col-xl-3 { flex: 0 0 25%; max-width: 25%; padding: 0 15px; }
            .col-xl-8 { flex: 0 0 66.666667%; max-width: 66.666667%; padding: 0 15px; }
            .col-xl-4 { flex: 0 0 33.333333%; max-width: 33.333333%; padding: 0 15px; }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Apply progress bar widths from data attributes to avoid CSS parser errors
            document.querySelectorAll('.pnl-progress').forEach(function(el) {
                el.style.width = el.getAttribute('data-width') + '%';
            });
        });
    </script>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Financial Chart
    const ctxFin = document.getElementById("financialChart");
    if(ctxFin) {
        new Chart(ctxFin, {
            type: 'line',
            data: {
                labels: JSON.parse('{!! json_encode($months ?? []) !!}'),
                datasets: [{
                    label: "Revenue",
                    borderColor: "#10B981",
                    backgroundColor: "rgba(16, 185, 129, 0.1)",
                    borderWidth: 3,
                    pointBackgroundColor: "#10B981",
                    pointBorderColor: "#fff",
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true,
                    data: JSON.parse('{!! json_encode($revenueData ?? []) !!}'),
                }, {
                    label: "Expense",
                    borderColor: "#EF4444",
                    backgroundColor: "rgba(239, 68, 68, 0.1)",
                    borderWidth: 3,
                    pointBackgroundColor: "#EF4444",
                    pointBorderColor: "#fff",
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true,
                    data: JSON.parse('{!! json_encode($expenseData ?? []) !!}'),
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }
                        }
                    },
                    tooltip: {
                        mode: 'index', intersect: false, backgroundColor: '#0F172A', padding: 12, cornerRadius: 8,
                        titleFont: { family: 'Poppins', size: 13 }, bodyFont: { family: 'Inter', size: 12 }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5], color: 'rgba(0,0,0,0.05)' }, ticks: { font: { family: 'Inter' } } },
                    x: { grid: { display: false }, ticks: { font: { family: 'Inter' } } }
                }
            }
        });
    }

    // Operational Distribution Chart
    const ctxOps = document.getElementById("activeBookingChart");
    if(ctxOps) {
        new Chart(ctxOps, {
            type: 'doughnut',
            data: {
                labels: JSON.parse("{!! json_encode($paymentStats['labels'] ?? []) !!}"),
                datasets: [{
                    data: JSON.parse("{!! json_encode($paymentStats['data'] ?? []) !!}"),
                    backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444'],
                    borderWidth: 0, hoverOffset: 15
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '80%',
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#0F172A', padding: 12, cornerRadius: 8, titleFont: { family: 'Poppins', size: 13 }, bodyFont: { family: 'Inter', size: 12 } }
                }
            }
        });
    }

    // Peak Season Predictor Chart (Phase 3)
    const ctxPeak = document.getElementById("peakSeasonChart");
    if(ctxPeak) {
        new Chart(ctxPeak, {
            type: 'bar',
            data: {
                labels: JSON.parse("{!! json_encode($peakSeasonLabels ?? []) !!}"),
                datasets: [{
                    label: "Predicted Demand", backgroundColor: "rgba(15, 23, 42, 0.8)", hoverBackgroundColor: "#0F172A", borderRadius: 8,
                    data: JSON.parse("{!! json_encode($peakSeasonData ?? []) !!}"),
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#0F172A', padding: 12, cornerRadius: 8, titleFont: { family: 'Poppins', size: 13 }, bodyFont: { family: 'Inter', size: 12 } }
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5], color: 'rgba(0,0,0,0.05)' }, ticks: { font: { family: 'Inter' } } },
                    x: { grid: { display: false }, ticks: { font: { family: 'Inter' } } }
                }
            }
        });
    }

    // Initialize Map
    if(document.getElementById('CarMap')) {
        const map = L.map('CarMap', { zoomControl: false, attributionControl: false }).setView([-6.9147, 107.6098], 13);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { maxZoom: 20 }).addTo(map);
        const CarData = JSON.parse("{!! json_encode($gpsCar) !!}");
        const markers = [];

        CarData.forEach(function(car) {
            if(car && car.lat && car.lng) {
                const statusColor = car.status === 'Moving' ? '#10B981' : (car.status === 'Idle' ? '#F59E0B' : '#64748B');
                const carIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style='background-color:${statusColor}; border:2px solid #fff; width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);'><i class='fas fa-car-side' style='font-size:16px;'></i></div>`,
                    iconSize: [40, 40], iconAnchor: [20, 20]
                });

                const marker = L.marker([car.lat, car.lng], {icon: carIcon}).addTo(map);
                marker.bindPopup(`
                    <div class="p-2" style="min-width: 150px;">
                        <strong class="d-block mb-1 text-main">${car.name}</strong>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-${car.status === 'Moving' ? 'success' : (car.status === 'Idle' ? 'warning' : 'secondary')} rounded-pill" style="font-size: 10px;">${car.status}</span>
                            <small class="text-muted">${car.speed}</small>
                        </div>
                        <div class="small border-top pt-2 mt-1">
                            <i class="far fa-clock me-1"></i> Updated ${car.last_update}
                        </div>
                    </div>
                `);
                markers.push({ instance: marker, data: car });
            }
        });

        // Simulate Live Movement (Minor jitter for demo)
        setInterval(() => {
            markers.forEach(m => {
                if(m.data.status === 'Moving') {
                    const lat = m.instance.getLatLng().lat + (Math.random() - 0.5) * 0.001;
                    const lng = m.instance.getLatLng().lng + (Math.random() - 0.5) * 0.001;
                    m.instance.setLatLng([lat, lng]);
                }
            });
        }, 3000);
    }

    if (typeof lucide !== 'undefined') { lucide.createIcons(); }
});
</script>
@endsection

