@extends('layouts.admin')

@section('title', 'Tambah Armada Baru - Siliwangi Rental')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-main mb-1">Tambah Armada Baru</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.cars.index') }}" class="text-decoration-none text-muted">Armada</a></li>
                    <li class="breadcrumb-item active text-secondary fw-medium" aria-current="page">Tambah Mobil</li>
                </ol>
            </nav>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-pill shadow-sm transition-all hov-move-left">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 animate__animated animate__shakeX">
        <div class="d-flex">
            <div class="me-3 fs-4">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div>
                <h5 class="alert-heading fw-bold">Oops! Terjadi Kesalahan</h5>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.cars.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row g-4">
            <!-- Left Column: Primary Information -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white border-0 py-3 ps-4 d-flex align-items-center">
                        <div class="icon-box bg-primary-light text-primary rounded-3 me-3 p-2">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h5 class="mb-0 fw-bold text-main">Informasi Utama</h5>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label for="car_name" class="form-label fw-semibold text-muted small text-uppercase">Nama Mobil <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-car text-muted"></i></span>
                                    <input type="text" class="form-control bg-light border-0 py-2" id="car_name" name="car_name" placeholder="Contoh: Toyota Avanza Veloz" value="{{ old('car_name') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="plate_number" class="form-label fw-semibold text-muted small text-uppercase">Nomor Polisi <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-hashtag text-muted"></i></span>
                                    <input type="text" class="form-control bg-light border-0 py-2" id="plate_number" name="plate_number" placeholder="B 1234 ABC" value="{{ old('plate_number') }}" required>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="brand_id" class="form-label fw-semibold text-muted small text-uppercase">Merek <span class="text-danger">*</span></label>
                                <select class="form-select bg-light border-0 py-2" id="brand_id" name="brand_id" required>
                                    <option value="" disabled selected>Pilih Merek</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="type_id" class="form-label fw-semibold text-muted small text-uppercase">Tipe Kendaraan <span class="text-danger">*</span></label>
                                <select class="form-select bg-light border-0 py-2" id="type_id" name="type_id" required>
                                    <option value="" disabled selected>Pilih Tipe</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="category" class="form-label fw-semibold text-muted small text-uppercase">Segmentasi <span class="text-danger">*</span></label>
                                <select class="form-select bg-light border-0 py-2" id="category" name="category" required>
                                    <option value="pribadi" {{ old('category') == 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                                    <option value="perusahaan" {{ old('category') == 'perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                                    <option value="both" {{ old('category') == 'both' ? 'selected' : '' }} selected>Keduanya (All)</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="branch_id" class="form-label fw-semibold text-muted small text-uppercase">Lokasi Cabang <span class="text-danger">*</span></label>
                                <select class="form-select bg-light border-0 py-2" id="branch_id" name="branch_id" required>
                                    <option value="" disabled selected>Pilih Cabang</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label fw-semibold text-muted small text-uppercase">Deskripsi & Spesifikasi Tambahan</label>
                                <textarea class="form-control bg-light border-0" id="description" name="description" rows="4" placeholder="Jelaskan detail mobil, fitur khusus, atau catatan lainnya...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white border-0 py-3 ps-4 d-flex align-items-center">
                        <div class="icon-box bg-secondary-light text-secondary rounded-3 me-3 p-2">
                            <i class="fas fa-sliders-h"></i>
                        </div>
                        <h5 class="mb-0 fw-bold text-main">Spesifikasi Teknis</h5>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="year" class="form-label fw-semibold text-muted small text-uppercase">Tahun <span class="text-danger">*</span></label>
                                <input type="number" class="form-control bg-light border-0 py-2" id="year" name="year" value="{{ old('year', date('Y')) }}" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="color" class="form-label fw-semibold text-muted small text-uppercase">Warna <span class="text-danger">*</span></label>
                                <input type="text" class="form-control bg-light border-0 py-2" id="color" name="color" placeholder="Hitam" value="{{ old('color') }}" required>
                            </div>

                            <div class="col-md-3">
                                <label for="seat" class="form-label fw-semibold text-muted small text-uppercase">Kapasitas <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control bg-light border-0 py-2" id="seat" name="seat" value="{{ old('seat', 5) }}" required>
                                    <span class="input-group-text bg-light border-0 text-muted small">Seat</span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="transmission" class="form-label fw-semibold text-muted small text-uppercase">Transmisi <span class="text-danger">*</span></label>
                                <select class="form-select bg-light border-0 py-2" id="transmission" name="transmission" required>
                                    <option value="Automatic" {{ old('transmission') == 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                    <option value="Manual" {{ old('transmission') == 'Manual' ? 'selected' : '' }}>Manual</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="fuel_type" class="form-label fw-semibold text-muted small text-uppercase">Bahan Bakar <span class="text-danger">*</span></label>
                                <select class="form-select bg-light border-0 py-2" id="fuel_type" name="fuel_type" required>
                                    <option value="Bensin" {{ old('fuel_type') == 'Bensin' ? 'selected' : '' }}>Bensin</option>
                                    <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                                    <option value="Listrik" {{ old('fuel_type') == 'Listrik' ? 'selected' : '' }}>Listrik</option>
                                    <option value="Hybrid" {{ old('fuel_type') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Pricing & Image -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-primary text-white p-2">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"><i class="fas fa-tag me-2 text-secondary"></i> Pengaturan Harga</h5>
                        <div class="mb-3">
                            <label for="daily_price" class="form-label small text-uppercase opacity-75">Sewa Harian (IDR)</label>
                            <div class="input-group border-0">
                                <span class="input-group-text bg-white bg-opacity-10 border-0 text-white">Rp</span>
                                <input type="number" class="form-control bg-white bg-opacity-20 border-0 text-white placeholder-light py-2" id="daily_price" name="daily_price" placeholder="0" value="{{ old('daily_price') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="monthly_price" class="form-label small text-uppercase opacity-75">Sewa Bulanan (IDR)</label>
                            <div class="input-group border-0">
                                <span class="input-group-text bg-white bg-opacity-10 border-0 text-white">Rp</span>
                                <input type="number" class="form-control bg-white bg-opacity-20 border-0 text-white placeholder-light py-2" id="monthly_price" name="monthly_price" placeholder="0" value="{{ old('monthly_price') }}" required>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label for="late_fee" class="form-label small text-uppercase opacity-75">Denda Telat / Jam</label>
                            <div class="input-group border-0">
                                <span class="input-group-text bg-white bg-opacity-10 border-0 text-white">Rp</span>
                                <input type="number" class="form-control bg-white bg-opacity-20 border-0 text-white placeholder-light py-2" id="late_fee" name="late_fee" placeholder="0" value="{{ old('late_fee') }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white border-0 py-3 ps-4 d-flex align-items-center">
                        <div class="icon-box bg-success-light text-success rounded-3 me-3 p-2">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h5 class="mb-0 fw-bold text-main">Status & Visibilitas</h5>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <div class="mb-3">
                            <label for="status" class="form-label fw-semibold text-muted small text-uppercase">Kondisi Saat Ini</label>
                            <select class="form-select bg-light border-0 py-2" id="status" name="status" required>
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Tersedia (Siap Jalan)</option>
                                <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>Sedang Disewa</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Perbaikan / Bengkel</option>
                            </select>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label for="is_available" class="form-label fw-semibold text-muted small text-uppercase">Publikasi</label>
                                <select class="form-select bg-light border-0 py-2" id="is_available" name="is_available" required>
                                    <option value="1" {{ old('is_available') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>Arsip</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="featured" class="form-label fw-semibold text-muted small text-uppercase">Unggulan</label>
                                <select class="form-select bg-light border-0 py-2" id="featured" name="featured" required>
                                    <option value="0" {{ old('featured') == '0' ? 'selected' : '' }}>Reguler</option>
                                    <option value="1" {{ old('featured') == '1' ? 'selected' : '' }}>Ya (Utama)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="stock" class="form-label fw-semibold text-muted small text-uppercase">Stok Unit</label>
                            <input type="number" class="form-control bg-light border-0 py-2" id="stock" name="stock" value="{{ old('stock', 1) }}" min="1" required>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white border-0 py-3 ps-4 d-flex align-items-center">
                        <div class="icon-box bg-info-light text-info rounded-3 me-3 p-2">
                            <i class="fas fa-image"></i>
                        </div>
                        <h5 class="mb-0 fw-bold text-main">Visual Armada</h5>
                    </div>
                    <div class="card-body p-4 pt-2">
                        <div class="image-upload-wrapper text-center p-4 border-2 border-dashed rounded-4 mb-2" id="dropZone" style="border-color: #e2e8f0; cursor: pointer;">
                            <i class="fas fa-cloud-upload-alt fs-1 text-muted mb-2"></i>
                            <p class="text-muted small mb-0">Klik atau seret foto mobil ke sini</p>
                            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="d-none">
                            <img id="previewImage" src="#" alt="Preview" class="img-fluid rounded-3 mt-3 d-none shadow-sm" style="max-height: 200px;">
                        </div>
                        <small class="text-muted d-block text-center">Format: JPG, PNG, WEBP. Maks 2MB.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Bottom Actions -->
        <div class="position-sticky bottom-0 bg-white border-top p-3 shadow-lg rounded-top-4 mt-4 animate__animated animate__slideInUp" style="z-index: 100; margin-left: -1.5rem; margin-right: -1.5rem; margin-bottom: -1.5rem;">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <p class="mb-0 text-muted d-none d-md-block"><i class="fas fa-info-circle me-1 text-secondary"></i> Pastikan semua data bertanda <span class="text-danger">*</span> telah diisi dengan benar.</p>
                <div class="ms-auto d-flex gap-2">
                    <button type="reset" class="btn btn-light px-4 py-2 rounded-pill">Reset Form</button>
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill shadow-primary">
                        <i class="fas fa-save me-2"></i> Simpan Armada
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('css')
<style>
    .bg-primary-light { background-color: rgba(15, 23, 42, 0.05); }
    .bg-secondary-light { background-color: rgba(212, 175, 55, 0.1); }
    .bg-success-light { background-color: rgba(16, 185, 129, 0.1); }
    .bg-info-light { background-color: rgba(59, 130, 246, 0.1); }
    
    .text-main { color: var(--text-main); }
    .text-secondary { color: var(--secondary) !important; }
    .btn-primary { background-color: var(--primary); border-color: var(--primary); }
    .btn-primary:hover { background-color: #1e293b; border-color: #1e293b; }
    
    .shadow-primary { box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.3); }
    
    .placeholder-light::placeholder { color: rgba(255, 255, 255, 0.5); }
    
    .transition-all { transition: all 0.3s ease; }
    .hov-move-left:hover { transform: translateX(-5px); }
    
    .form-control:focus, .form-select:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(15, 23, 42, 0.05);
        border: 1px solid var(--secondary) !important;
    }
    
    .image-upload-wrapper:hover {
        background-color: #f8fafc;
        border-color: var(--secondary) !important;
    }
    
    .border-dashed { border-style: dashed !important; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('thumbnail');
        const previewImage = document.getElementById('previewImage');
        const uploadIcon = dropZone.querySelector('i');
        const uploadText = dropZone.querySelector('p');

        dropZone.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('d-none');
                    uploadIcon.classList.add('d-none');
                    uploadText.classList.add('d-none');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Drag and drop
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = 'var(--secondary)';
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.style.borderColor = '#e2e8f0';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            fileInput.files = e.dataTransfer.files;
            fileInput.dispatchEvent(new Event('change'));
        });
    });
</script>
@endpush
@endsection
