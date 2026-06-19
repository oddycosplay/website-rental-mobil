@extends('layouts.sbadmin')

@section('title', 'Tambah Pengeluaran')

@section('content')
<h1 class="mt-4">Tambah Pengeluaran</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.expenses.index') }}">Pengeluaran</a></li>
    <li class="breadcrumb-item active">Tambah</li>
</ol>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4 shadow">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-plus me-1"></i> Form Pengeluaran
            </div>
            <div class="card-body">
                <form action="{{ route('admin.expenses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal</label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ date('Y-m-d') }}" required>
                            @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="expense_category_id" class="form-select @error('expense_category_id') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('expense_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Cabang</label>
                            <select name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" required>
                                <option value="">Pilih Cabang</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @error('branch_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jumlah (Rp)</label>
                            <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" placeholder="Contoh: 50000" required>
                            @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Masukkan detail pengeluaran..."></textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Lampiran / Bukti (Optional)</label>
                        <input type="file" name="attachment" class="form-control @error('attachment') is-invalid @enderror">
                        <div class="small text-muted mt-1">Upload nota atau bukti bayar (Format: JPG, PNG, PDF | Max 2MB)</div>
                        @error('attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-info-circle text-primary"></i> Tips Pengeluaran</h5>
                <p class="card-text small text-muted">
                    Pastikan kategori yang dipilih sudah benar untuk mempermudah pembuatan laporan keuangan nantinya. Jika kategori tidak ada, Anda bisa menambahkannya di menu <strong>Kategori Biaya</strong>.
                </p>
                <hr>
                <a href="{{ route('admin.expense-categories.index') }}" class="btn btn-outline-primary btn-sm w-100">Kelola Kategori</a>
            </div>
        </div>
    </div>
</div>
@endsection
