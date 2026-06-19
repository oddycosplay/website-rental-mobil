@extends('layouts.admin')

@section('title', 'Manajemen Merk - Siliwangi Rental')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="h2 fw-bold text-main mb-1">Manajemen Merk</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active text-secondary fw-medium" aria-current="page">Merk Mobil</li>
                </ol>
            </nav>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.car-brands.create') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm transition-all hov-move-up">
                <i class="fas fa-plus me-2"></i> Tambah Merk Baru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 animate__animated animate__fadeIn">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle me-2 fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <!-- Brands Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden animate__animated animate__fadeInUp">
        <div class="card-header bg-white border-0 py-3 ps-4">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-primary-light text-primary rounded-3 me-3 p-2">
                    <i class="fas fa-tags"></i>
                </div>
                <h5 class="mb-0 fw-bold text-main">Daftar Merk Mobil</h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small text-uppercase fw-bold">
                            <th class="ps-4 py-3 border-0 text-center" style="width: 80px;">No</th>
                            <th class="py-3 border-0">Logo</th>
                            <th class="py-3 border-0">Nama Merk</th>
                            <th class="py-3 border-0">Slug</th>
                            <th class="py-3 border-0 text-center">Koleksi Armada</th>
                            <th class="pe-4 py-3 border-0 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($brands as $brand)
                        <tr class="border-bottom transition-all">
                            <td class="ps-4 py-3 text-center text-muted fw-medium">{{ $loop->iteration }}</td>
                            <td class="py-3">
                                @if($brand->logo)
                                    <div class="brand-logo-container p-2 rounded-3 bg-white shadow-sm d-inline-block border">
                                        <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }}" style="height: 35px; object-fit: contain;">
                                    </div>
                                @else
                                    <div class="brand-logo-container p-2 rounded-3 bg-light d-inline-block text-muted small border border-dashed">
                                        No Logo
                                    </div>
                                @endif
                            </td>
                            <td class="py-3">
                                <div class="fw-bold text-main fs-6">{{ $brand->name }}</div>
                            </td>
                            <td class="py-3">
                                <code class="small bg-light px-2 py-1 rounded text-primary">{{ $brand->slug }}</code>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-primary-light text-primary rounded-pill px-3 py-2 fw-semibold">
                                    {{ $brand->cars->count() }} Unit
                                </span>
                            </td>
                            <td class="pe-4 py-3 text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('admin.car-brands.edit', $brand->id) }}" class="btn btn-icon btn-outline-warning rounded-circle shadow-sm transition-all" title="Edit">
                                        <i class="fas fa-edit small"></i>
                                    </a>
                                    <form action="{{ route('admin.car-brands.destroy', $brand->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus merk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-outline-danger rounded-circle shadow-sm transition-all" title="Hapus">
                                            <i class="fas fa-trash small"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="opacity-25 mb-3 text-muted">
                                    <i class="fas fa-tags fa-4x"></i>
                                </div>
                                <h5 class="fw-bold text-muted">Belum Ada Data Merk</h5>
                                <p class="text-muted small">Silakan tambahkan merk mobil pertama Anda.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('css')
<style>
    .bg-primary-light { background-color: rgba(15, 23, 42, 0.05); }
    .text-main { color: var(--text-main); }
    .text-secondary { color: var(--secondary) !important; }
    
    .transition-all { transition: all 0.3s ease; }
    .hov-move-up:hover { transform: translateY(-5px); }
    
    .btn-icon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
    
    .btn-outline-warning:hover { background-color: #ffc107; color: #fff; border-color: #ffc107; }
    .btn-outline-danger:hover { background-color: #dc3545; color: #fff; border-color: #dc3545; }
    
    .brand-logo-container { transition: all 0.3s ease; }
    tr:hover .brand-logo-container { transform: scale(1.1); border-color: var(--secondary) !important; }
    
    .table-hover tbody tr:hover { background-color: rgba(15, 23, 42, 0.02); }
</style>
@endpush
@endsection
