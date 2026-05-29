@extends('layouts.admin')

@section('title', 'Vehicle Inspection Hub - Siliwangi Rental')

@section('styles')
<style>
    /* Premium Page Styling */
    .ins-card {
        border-radius: 16px;
        border: 1px solid var(--card-border);
        background: var(--card-bg);
        box-shadow: var(--card-shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .ins-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--card-shadow-hover);
    }
    .metric-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
    }
    
    /* Interactive Visual Radio Cards */
    .visual-radio-group {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }
    @media (max-width: 768px) {
        .visual-radio-group {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    .visual-radio-card {
        border: 2px solid #E2E8F0;
        border-radius: 12px;
        padding: 12px 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        background: #F8FAFC;
    }
    .visual-radio-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0; height: 0;
    }
    .visual-radio-card:hover {
        border-color: #CBD5E1;
        background: #F1F5F9;
    }
    .visual-radio-card.active {
        border-color: var(--secondary) !important;
        background: rgba(212, 175, 55, 0.05);
        color: var(--secondary-dark);
        font-weight: 700;
        box-shadow: 0 4px 6px -1px rgba(212, 175, 55, 0.1);
    }
    
    /* Audit Grid layout */
    .audit-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    @media (max-width: 576px) {
        .audit-grid {
            grid-template-columns: 1fr;
        }
    }
    .audit-box {
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 16px;
        background: #FFF;
    }
    
    /* Custom Scrollbar for Logs */
    .custom-scroll {
        max-height: 480px;
        overflow-y: auto;
    }
    .custom-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: #CBD5E1;
        border-radius: 3px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: #94A3B8;
    }

    /* Modal premium styles */
    .modal-content-custom {
        border-radius: 20px;
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 p-4 rounded-4" style="background: linear-gradient(135deg, #0F172A 0%, #1e293b 100%); color: white; box-shadow: 0 10px 20px rgba(0,0,0,0.15);">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <h2 class="h3 fw-bold mb-1 d-flex align-items-center gap-2">
                            <i class="fas fa-clipboard-check text-warning"></i> Vehicle Inspection Hub
                        </h2>
                        <p class="mb-0 opacity-75">Kelola serah-terima (Check-out) & pengembalian (Check-in) armada mobil Siliwangi Rental.</p>
                    </div>
                    <button class="btn btn-warning btn-md rounded-pill px-4 fw-bold shadow" onclick="triggerManualAudit()">
                        <i class="fas fa-plus-circle me-1"></i> Audit Inspeksi Manual
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Metrics Grid -->
    <div class="row g-4 mb-4">
        <!-- Card 1: Total Inspeksi -->
        <div class="col-xl-3 col-sm-6">
            <div class="card ins-card p-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted fw-semibold text-uppercase font-sans fs-7" style="letter-spacing: 0.5px;">Total Inspeksi</span>
                        <h3 class="fw-bold mt-1 mb-0 text-dark">{{ $totalInspections }}</h3>
                    </div>
                    <div class="metric-icon" style="background: rgba(212, 175, 55, 0.1); color: var(--secondary);">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Menunggu Inspeksi -->
        <div class="col-xl-3 col-sm-6">
            <div class="card ins-card p-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted fw-semibold text-uppercase font-sans fs-7" style="letter-spacing: 0.5px;">Menunggu Audit</span>
                        <h3 class="fw-bold mt-1 mb-0 text-dark">{{ $pendingInspections }}</h3>
                    </div>
                    <div class="metric-icon" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Armada Siap -->
        <div class="col-xl-3 col-sm-6">
            <div class="card ins-card p-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted fw-semibold text-uppercase font-sans fs-7" style="letter-spacing: 0.5px;">Armada Ready</span>
                        <h3 class="fw-bold mt-1 mb-0 text-dark">{{ $readyCarsCount }}</h3>
                    </div>
                    <div class="metric-icon" style="background: rgba(16, 185, 129, 0.1); color: #10B981;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Dalam Perbaikan -->
        <div class="col-xl-3 col-sm-6">
            <div class="card ins-card p-3 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted fw-semibold text-uppercase font-sans fs-7" style="letter-spacing: 0.5px;">Dalam Perbaikan</span>
                        <h3 class="fw-bold mt-1 mb-0 text-dark">{{ $maintenanceCarsCount }}</h3>
                    </div>
                    <div class="metric-icon" style="background: rgba(239, 68, 68, 0.1); color: #EF4444;">
                        <i class="fas fa-wrench"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="row g-4">
        <!-- Left Side: Queue and History Logs -->
        <div class="col-lg-8">
            <!-- 1. Antrean Inspeksi Booking Aktif -->
            <div class="card ins-card border-0 p-4 mb-4">
                <h4 class="h5 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <i class="fas fa-exchange-alt text-primary"></i> Antrean Inspeksi Operasional
                </h4>
                @if($activeBookings->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-calendar-check opacity-25 mb-3" style="font-size: 48px;"></i>
                        <p class="mb-0 fw-semibold">Tidak ada antrean inspeksi aktif saat ini.</p>
                        <p class="fs-7 opacity-75">Semua serah-terima dan pengembalian sewa kendaraan telah diselesaikan.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr class="text-muted fs-7 text-uppercase font-sans">
                                    <th>Ref / Transaksi</th>
                                    <th>Mobil & Pelanggan</th>
                                    <th>Metode Sewa</th>
                                    <th class="text-center">Status Operasional</th>
                                    <th class="text-end">Aksi Audit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeBookings as $booking)
                                    <tr>
                                        <td>
                                            <span class="fw-bold font-monospace text-dark">{{ $booking->booking_code }}</span>
                                            <div class="fs-7 text-muted">{{ $booking->created_at->format('d M Y') }}</div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($booking->car && $booking->car->thumbnail)
                                                    <img src="{{ asset('storage/' . $booking->car->thumbnail) }}" class="rounded" style="width: 48px; height: 32px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 32px; font-size: 10px;">CAR</div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $booking->car->car_name ?? 'Mobil' }}</div>
                                                    <div class="fs-7 text-muted">{{ $booking->customer->name ?? 'Pelanggan' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark text-capitalize font-sans">{{ $booking->driver_id ? 'Dengan Sopir' : 'Lepas Kunci' }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($booking->booking_status === 'confirmed')
                                                <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill fw-bold fs-7">
                                                    <i class="fas fa-sign-out-alt me-1"></i> Check-out (Serah)
                                                </span>
                                            @elseif($booking->booking_status === 'ongoing')
                                                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold fs-7">
                                                    <i class="fas fa-sign-in-alt me-1"></i> Check-in (Terima)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($booking->booking_status === 'confirmed')
                                                <button class="btn btn-warning btn-sm rounded-pill px-3 fw-bold shadow-sm"
                                                    onclick="openInspectionModal('check-out', {{ $booking->id }}, '{{ $booking->car->car_name }} ({{ $booking->car->plate_number }})', {{ $booking->car->mileage ?? 0 }})">
                                                    Check-out <i class="fas fa-arrow-right ms-1"></i>
                                                </button>
                                            @elseif($booking->booking_status === 'ongoing')
                                                <button class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm"
                                                    onclick="openInspectionModal('check-in', {{ $booking->id }}, '{{ $booking->car->car_name }} ({{ $booking->car->plate_number }})', {{ $booking->car->mileage ?? 0 }})">
                                                    Check-in <i class="fas fa-arrow-left ms-1"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- 2. Log Riwayat Audit Inspeksi Terakhir -->
            <div class="card ins-card border-0 p-4">
                <h4 class="h5 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <i class="fas fa-history text-muted"></i> Log Riwayat Audit Inspeksi
                </h4>
                @if($inspectionsLog->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-clipboard-list opacity-25 mb-3" style="font-size: 48px;"></i>
                        <p class="mb-0 fw-semibold">Belum ada riwayat inspeksi tercatat.</p>
                        <p class="fs-7 opacity-75">Silakan lakukan inspeksi check-out/check-in terlebih dahulu.</p>
                    </div>
                @else
                    <div class="custom-scroll">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr class="text-muted fs-7 text-uppercase font-sans">
                                        <th>Waktu / Kode</th>
                                        <th>Armada</th>
                                        <th>Inspektur</th>
                                        <th class="text-center">Tipe</th>
                                        <th class="text-center">Kondisi</th>
                                        <th class="text-end">Rincian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inspectionsLog as $idx => $log)
                                        <tr>
                                            <td>
                                                <span class="text-dark fw-semibold">{{ \Carbon\Carbon::parse($log->inspection_date)->format('d M Y, H:i') }}</span>
                                                <div class="fs-7 text-muted font-monospace">{{ $log->booking_code }}</div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-dark">{{ $log->car_name }}</span>
                                                <div class="fs-7 text-muted">{{ $log->plate_number }}</div>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-secondary">{{ $log->inspector }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($log->type === 'check-out')
                                                    <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill">Out</span>
                                                @else
                                                    <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-pill">In</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $isBodyOk = ($log->checklist['body'] ?? 'ok') === 'ok';
                                                    $isEngineOk = ($log->checklist['engine'] ?? 'ok') === 'ok';
                                                    $isInteriorOk = ($log->checklist['interior'] ?? 'ok') === 'ok';
                                                    $isTiresOk = ($log->checklist['tires'] ?? 'ok') === 'ok';
                                                    $hasIssues = !$isBodyOk || !$isEngineOk || !$isInteriorOk || !$isTiresOk;
                                                @endphp
                                                @if($hasIssues)
                                                    <span class="badge bg-danger-subtle text-danger px-2.5 py-1 rounded-pill fw-bold">
                                                        <i class="fas fa-exclamation-triangle me-1"></i> Bermasalah
                                                    </span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success px-2.5 py-1 rounded-pill fw-bold">
                                                        <i class="fas fa-check-circle me-1"></i> Prima
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-light btn-sm rounded-circle shadow-sm" onclick="showLogDetails({{ json_encode($log) }})">
                                                    <i class="fas fa-search-plus text-primary"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Side: Armada Status & Quick Overview -->
        <div class="col-lg-4">
            <div class="card ins-card border-0 p-4 mb-4">
                <h4 class="h5 fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <i class="fas fa-car-side text-secondary"></i> Status & Odometer Armada
                </h4>
                <p class="fs-7 text-muted mb-4">Kondisi terkini seluruh armada mobil berdasarkan laporan odometer (mil).</p>
                <div class="d-flex flex-column gap-3">
                    @foreach($cars as $car)
                        <div class="d-flex align-items-center justify-content-between p-2 rounded-3" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                            <div class="d-flex align-items-center gap-2">
                                @if($car->thumbnail)
                                    <img src="{{ asset('storage/' . $car->thumbnail) }}" class="rounded" style="width: 50px; height: 35px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 35px; font-size: 10px;">CAR</div>
                                @endif
                                <div>
                                    <div class="fw-bold text-dark fs-7" style="line-height: 1.2;">{{ $car->car_name }}</div>
                                    <div class="fs-8 text-muted">{{ $car->plate_number }}</div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-dark fs-7">{{ number_format($car->mileage ?? 0, 0, ',', '.') }} KM</div>
                                @if($car->status === 'available')
                                    <span class="badge bg-success-subtle text-success fs-8 px-2 py-0.5 rounded-pill">Ready</span>
                                @elseif($car->status === 'active' || $car->status === 'rented')
                                    <span class="badge bg-primary-subtle text-primary fs-8 px-2 py-0.5 rounded-pill">rented</span>
                                @elseif($car->status === 'maintenance')
                                    <span class="badge bg-danger-subtle text-danger fs-8 px-2 py-0.5 rounded-pill">Service</span>
                                @else
                                    <span class="badge bg-light text-dark fs-8 px-2 py-0.5 rounded-pill">{{ $car->status }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Operational Guidelines Card -->
            <div class="card border-0 p-4 rounded-4" style="background: linear-gradient(135deg, #1E3A8A 0%, #3B82F6 100%); color: white; box-shadow: var(--card-shadow);">
                <h5 class="fw-bold mb-2"><i class="fas fa-info-circle text-warning"></i> Panduan Lapangan</h5>
                <ul class="fs-7 opacity-90 ps-3 mb-0">
                    <li class="mb-2"><strong>Lepas Kunci:</strong> Audit ketat pada goresan baru di bodi dan kebersihan interior.</li>
                    <li class="mb-2"><strong>Dengan Sopir:</strong> Audit difokuskan pada tingkat bahan bakar dan indikator mesin/oli.</li>
                    <li><strong>Emergency:</strong> Jika terdeteksi kebocoran ban atau rem, alihkan status mobil ke <strong>Service</strong>.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL FORM INSPEKSI ================= -->
<div class="modal fade" id="inspectionFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header border-0 pb-0" style="padding: 24px;">
                <h4 class="modal-title fw-bold text-dark" id="modalTitle">Pencatatan Inspeksi Kendaraan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="inspectionForm">
                @csrf
                <input type="hidden" name="booking_id" id="formBookingId">
                <input type="hidden" name="type" id="formType">
                
                <div class="modal-body" style="padding: 24px;">
                    <!-- Info Mobil Banner -->
                    <div class="p-3 bg-light rounded-4 mb-4 border d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted fs-8 text-uppercase fw-semibold">Armada Yang Diaudit</span>
                            <h5 class="fw-bold text-primary mb-0" id="formCarInfo">Toyota Avanza (B 1234 CD)</h5>
                        </div>
                        <div class="text-end">
                            <span class="text-muted fs-8 text-uppercase fw-semibold">Odometer Awal</span>
                            <h5 class="fw-bold text-dark mb-0"><span id="formCurrentMileage">15.000</span> KM</h5>
                        </div>
                    </div>

                    <!-- Input Odometer & Fuel Level -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Odometer / Mileage Saat Ini (KM)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                                <input type="number" class="form-control" name="mileage" id="formInputMileage" min="0" required placeholder="Contoh: 15150">
                            </div>
                            <span class="fs-8 text-muted mt-1 d-block"><i class="fas fa-exclamation-circle text-info"></i> Masukkan nilai KM yang setara atau lebih tinggi dari odometer awal.</span>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Tingkat Bahan Bakar (Bensin/Solar)</label>
                            <input type="hidden" name="fuel_level" id="formFuelLevel" value="full">
                            <div class="visual-radio-group">
                                <div class="visual-radio-card" data-val="empty" onclick="selectFuel('empty', this)">
                                    <i class="fas fa-gas-pump text-danger d-block mb-1"></i>
                                    <span class="fs-8">Empty</span>
                                </div>
                                <div class="visual-radio-card" data-val="quarter" onclick="selectFuel('quarter', this)">
                                    <i class="fas fa-gas-pump text-warning d-block mb-1"></i>
                                    <span class="fs-8">1/4</span>
                                </div>
                                <div class="visual-radio-card" data-val="half" onclick="selectFuel('half', this)">
                                    <i class="fas fa-gas-pump text-primary d-block mb-1"></i>
                                    <span class="fs-8">1/2</span>
                                </div>
                                <div class="visual-radio-card active" data-val="full" onclick="selectFuel('full', this)">
                                    <i class="fas fa-gas-pump text-success d-block mb-1"></i>
                                    <span class="fs-8">Full</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Checklist Komponen Visual -->
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-tasks text-secondary"></i> Checklist Audit Komponen Fisik</h5>
                    
                    <div class="audit-grid mb-4">
                        <!-- 1. Bodi & Eksterior -->
                        <div class="audit-box">
                            <label class="form-label fw-bold text-dark d-flex align-items-center gap-2">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background: #3B82F6;"></span> Bodi & Eksterior
                            </label>
                            <input type="hidden" name="checklist[body]" id="chkBody" value="ok">
                            <div class="d-flex flex-wrap gap-1.5 mt-2">
                                <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-pill active" onclick="selectChecklist('body', 'ok', this)">OK</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-pill" onclick="selectChecklist('body', 'scratch', this)">Goresan</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-pill" onclick="selectChecklist('body', 'dent', this)">Penyok</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-pill" onclick="selectChecklist('body', 'damaged', this)">Pecah/Rusak</button>
                            </div>
                        </div>

                        <!-- 2. Mesin & Transmisi -->
                        <div class="audit-box">
                            <label class="form-label fw-bold text-dark d-flex align-items-center gap-2">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background: #10B981;"></span> Mesin & Transmisi
                            </label>
                            <input type="hidden" name="checklist[engine]" id="chkEngine" value="ok">
                            <div class="d-flex flex-wrap gap-1.5 mt-2">
                                <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-pill active" onclick="selectChecklist('engine', 'ok', this)">OK</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-pill" onclick="selectChecklist('engine', 'noisy', this)">Bising</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-pill" onclick="selectChecklist('engine', 'check-engine', this)">Indikator ON</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-pill" onclick="selectChecklist('engine', 'needs-service', this)">Butuh Servis</button>
                            </div>
                        </div>

                        <!-- 3. Interior & AC -->
                        <div class="audit-box">
                            <label class="form-label fw-bold text-dark d-flex align-items-center gap-2">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background: #F59E0B;"></span> Interior & AC
                            </label>
                            <input type="hidden" name="checklist[interior]" id="chkInterior" value="ok">
                            <div class="d-flex flex-wrap gap-1.5 mt-2">
                                <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-pill active" onclick="selectChecklist('interior', 'ok', this)">OK</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-pill" onclick="selectChecklist('interior', 'dirty', this)">Kotor/Bau</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-pill" onclick="selectChecklist('interior', 'damaged', this)">Sobek/Rusak</button>
                            </div>
                        </div>

                        <!-- 4. Ban & Pengereman -->
                        <div class="audit-box">
                            <label class="form-label fw-bold text-dark d-flex align-items-center gap-2">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background: #EF4444;"></span> Ban, Kaki & Rem
                            </label>
                            <input type="hidden" name="checklist[tires]" id="chkTires" value="ok">
                            <div class="d-flex flex-wrap gap-1.5 mt-2">
                                <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-pill active" onclick="selectChecklist('tires', 'ok', this)">OK</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-pill" onclick="selectChecklist('tires', 'worn', this)">Gundul/Aus</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary px-2.5 py-1 rounded-pill" onclick="selectChecklist('tires', 'flat', this)">Bocor/Kempis</button>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Temuan & Inspektur -->
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold text-dark">Catatan Temuan Lapangan / Kerusakan</label>
                            <textarea class="form-control" name="notes" id="formNotes" rows="3" placeholder="Contoh: Bodi kiri ada goresan halus 3cm, mesin normal."></textarea>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold text-dark">Petugas Inspektur (Auditor)</label>
                            <input type="text" class="form-control" name="inspector" id="formInspector" value="{{ auth()->user()->name ?? 'Inspector Lapangan' }}" required placeholder="Nama lengkap petugas">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0" style="padding: 24px;">
                    <button type="button" class="btn btn-light px-4 rounded-pill fw-semibold" data-bs-dismiss="modal">Batalkan</button>
                    <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow-md" id="submitBtn">Simpan Hasil Audit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= MODAL DETAIL AUDIT RIWAYAT ================= -->
<div class="modal fade" id="inspectionDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header border-0 pb-0" style="padding: 24px;">
                <h4 class="modal-title fw-bold text-dark">Rincian Hasil Audit Inspeksi</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <div class="p-3 bg-light rounded-4 border mb-4 text-center">
                    <span id="detailTypeBadge" class="badge px-3 py-1.5 rounded-pill mb-2 fw-bold text-uppercase">-</span>
                    <h5 id="detailCarName" class="fw-bold text-dark mb-0">-</h5>
                    <div id="detailPlateNumber" class="text-secondary fs-7">-</div>
                </div>

                <div class="d-flex justify-content-between border-bottom pb-2 mb-3 fs-7">
                    <span class="text-muted">Tanggal Audit</span>
                    <span id="detailDate" class="fw-bold text-dark">-</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-3 fs-7">
                    <span class="text-muted">Kode Booking</span>
                    <span id="detailBookingCode" class="fw-bold text-dark font-monospace">-</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-3 fs-7">
                    <span class="text-muted">Odometer (KM)</span>
                    <span id="detailMileage" class="fw-bold text-dark">-</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-3 fs-7">
                    <span class="text-muted">Bahan Bakar</span>
                    <span id="detailFuel" class="fw-bold text-dark text-capitalize">-</span>
                </div>
                <div class="d-flex justify-content-between border-bottom pb-2 mb-3 fs-7">
                    <span class="text-muted">Inspektur Auditor</span>
                    <span id="detailInspector" class="fw-bold text-dark">-</span>
                </div>

                <h6 class="fw-bold text-dark mt-4 mb-3"><i class="fas fa-check-double text-secondary"></i> Hasil Checklist Komponen:</h6>
                
                <div class="row g-2 mb-4" id="detailChecklistContainer">
                    <!-- Dinamis via JS -->
                </div>

                <div class="p-3 rounded-3" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                    <span class="text-muted fs-8 text-uppercase fw-semibold d-block">Catatan Auditor</span>
                    <p id="detailNotes" class="mb-0 text-dark font-sans fs-7 mt-1">-</p>
                </div>
            </div>
            <div class="modal-footer border-0" style="padding: 24px;">
                <button type="button" class="btn btn-secondary w-100 rounded-pill py-2 fw-bold" data-bs-dismiss="modal">Tutup Rincian</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Inisialisasi variabel global
    let inspectionFormModal;
    let inspectionDetailModal;

    document.addEventListener('DOMContentLoaded', function() {
        inspectionFormModal = new bootstrap.Modal(document.getElementById('inspectionFormModal'));
        inspectionDetailModal = new bootstrap.Modal(document.getElementById('inspectionDetailModal'));
    });

    // Menangani aksi pembukaan formulir audit inspeksi
    function openInspectionModal(type, bookingId, carInfo, currentMileage) {
        document.getElementById('formType').value = type;
        document.getElementById('formBookingId').value = bookingId;
        document.getElementById('formCarInfo').innerText = carInfo;
        document.getElementById('formCurrentMileage').innerText = new Intl.NumberFormat('id-ID').format(currentMileage);
        
        // Atur nilai input mileage minimal & prefilled
        const mileageInput = document.getElementById('formInputMileage');
        mileageInput.min = currentMileage;
        mileageInput.value = currentMileage;

        // Reset checklist visual
        resetChecklists();

        // Atur Judul Modal berdasarkan Tipe
        const modalTitle = document.getElementById('modalTitle');
        if (type === 'check-out') {
            modalTitle.innerHTML = `<i class="fas fa-sign-out-alt text-warning me-1"></i> Inspeksi Serah Terima (Check-out)`;
        } else {
            modalTitle.innerHTML = `<i class="fas fa-sign-in-alt text-primary me-1"></i> Inspeksi Pengembalian (Check-in)`;
        }

        inspectionFormModal.show();
    }

    // Trigger audit manual
    function triggerManualAudit() {
        Swal.fire({
            title: 'Audit Inspeksi Manual',
            text: 'Silakan pilih antrean pemesanan aktif yang tertera di tabel operasional untuk melakukan audit serah-terima/pengembalian mobil.',
            icon: 'info',
            confirmButtonText: 'Saya Mengerti',
            confirmButtonColor: '#0F172A',
            customClass: {
                popup: 'rounded-4'
            }
        });
    }

    // Fungsi reset checklist
    function resetChecklists() {
        // Reset fuel select
        document.getElementById('formFuelLevel').value = 'full';
        document.querySelectorAll('.visual-radio-card').forEach(el => el.classList.remove('active'));
        document.querySelector('.visual-radio-card[data-val="full"]').classList.add('active');

        // Reset checklists button
        const categories = ['body', 'engine', 'interior', 'tires'];
        categories.forEach(cat => {
            document.getElementById('chk' + cat.charAt(0).toUpperCase() + cat.slice(1)).value = 'ok';
            const buttons = document.querySelectorAll(`button[onclick*="selectChecklist('${cat}'"]`);
            buttons.forEach(btn => btn.classList.remove('active'));
            if (buttons.length > 0) buttons[0].classList.add('active');
        });

        // Reset text
        document.getElementById('formNotes').value = '';
    }

    // Penanganan pilihan visual radio bensin
    function selectFuel(val, element) {
        document.getElementById('formFuelLevel').value = val;
        document.querySelectorAll('.visual-radio-card').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }

    // Penanganan pilihan visual checklist tombol
    function selectChecklist(category, val, element) {
        const inputId = 'chk' + category.charAt(0).toUpperCase() + category.slice(1);
        document.getElementById(inputId).value = val;
        
        const buttons = element.parentNode.querySelectorAll('button');
        buttons.forEach(btn => btn.classList.remove('active'));
        element.classList.add('active');
    }

    // AJAX Form Submit
    document.getElementById('inspectionForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('submitBtn');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...`;

        try {
            const formData = new FormData(this);
            const response = await fetch('{{ route('admin.car-inspections.store') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                inspectionFormModal.hide();
                Swal.fire({
                    title: 'Berhasil!',
                    text: result.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-4'
                    }
                });

                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                Swal.fire({
                    title: 'Gagal!',
                    text: result.message || 'Terjadi kesalahan saat menyimpan data.',
                    icon: 'error',
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#EF4444'
                });
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        } catch (error) {
            console.error(error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan jaringan atau server error.',
                icon: 'error',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#EF4444'
            });
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    });

    // Menampilkan log rincian dari log yang sudah diinspeksi
    function showLogDetails(log) {
        document.getElementById('detailCarName').innerText = log.car_name;
        document.getElementById('detailPlateNumber').innerText = log.plate_number;
        document.getElementById('detailDate').innerText = new Date(log.inspection_date).toLocaleString('id-ID', {
            day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
        }) + ' WIB';
        document.getElementById('detailBookingCode').innerText = log.booking_code;
        document.getElementById('detailMileage').innerText = new Intl.NumberFormat('id-ID').format(log.mileage) + ' KM';
        
        // Fuel level mapping
        const fuelMapping = {
            'empty': 'Kosong',
            'quarter': '1/4 (Seperempat)',
            'half': '1/2 (Setengah)',
            'three-quarters': '3/4 (Tiga Perempat)',
            'full': 'Penuh (Full)'
        };
        document.getElementById('detailFuel').innerText = fuelMapping[log.fuel_level] || log.fuel_level;
        document.getElementById('detailInspector').innerText = log.inspector;
        document.getElementById('detailNotes').innerText = log.notes || '-';

        // Tipe Badge
        const typeBadge = document.getElementById('detailTypeBadge');
        if (log.type === 'check-out') {
            typeBadge.className = 'badge bg-warning text-dark px-3 py-1.5 rounded-pill mb-2 fw-bold text-uppercase';
            typeBadge.innerText = 'Check-out (Serah Terima)';
        } else {
            typeBadge.className = 'badge bg-primary text-white px-3 py-1.5 rounded-pill mb-2 fw-bold text-uppercase';
            typeBadge.innerText = 'Check-in (Pengembalian)';
        }

        // Generate Checklist visual
        const container = document.getElementById('detailChecklistContainer');
        container.innerHTML = '';

        const checklists = log.checklist || {};
        const labels = {
            'body': { title: 'Bodi & Eksterior', mapping: { 'ok': 'OK (Mulus)', 'scratch': 'Goresan Ringan', 'dent': 'Penyok', 'damaged': 'Rusak Berat' } },
            'engine': { title: 'Mesin & Transmisi', mapping: { 'ok': 'OK (Normal)', 'noisy': 'Bising', 'check-engine': 'Indikator ON', 'needs-service': 'Butuh Servis' } },
            'interior': { title: 'Interior & AC', mapping: { 'ok': 'OK (Bersih)', 'dirty': 'Kotor/Bau', 'damaged': 'Rusak/Sobek' } },
            'tires': { title: 'Ban & Pengereman', mapping: { 'ok': 'OK (Bagus)', 'worn': 'Aus/Gundul', 'flat': 'Bocor/Kempis' } }
        };

        Object.keys(labels).forEach(key => {
            const val = checklists[key] || 'ok';
            const info = labels[key];
            const isOk = val === 'ok';
            
            let statusBadge = '';
            if (isOk) {
                statusBadge = `<span class="badge bg-success-subtle text-success fs-8"><i class="fas fa-check-circle me-1"></i> ${info.mapping[val] || val}</span>`;
            } else {
                statusBadge = `<span class="badge bg-danger-subtle text-danger fs-8"><i class="fas fa-exclamation-triangle me-1"></i> ${info.mapping[val] || val}</span>`;
            }

            container.innerHTML += `
                <div class="col-6 mb-2">
                    <div class="p-2 border rounded-3 bg-light d-flex flex-column gap-1">
                        <span class="fw-semibold text-dark fs-8">${info.title}</span>
                        ${statusBadge}
                    </div>
                </div>
            `;
        });

        inspectionDetailModal.show();
    }
</script>
@endsection
