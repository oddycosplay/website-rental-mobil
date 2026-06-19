@extends('layouts.sbadmin')

@section('title', 'Edit Merk')

@section('content')
<h1 class="mt-4">Edit Merk</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.car-brands.index') }}">Merk</a></li>
    <li class="breadcrumb-item active">Edit</li>
</ol>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-edit me-1"></i>
                Form Edit Merk
            </div>
            <div class="card-body">
                <form action="{{ route('admin.car-brands.update', $car_brand->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Merk</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $car_brand->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="logo" class="form-label">Logo Merk</label>
                        @if($car_brand->logo)
                            <div class="mb-2">
                                <img src="{{ Storage::url($car_brand->logo) }}" alt="Logo Current" style="height: 50px;" class="rounded">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo">
                        <div class="form-text">Kosongkan jika tidak ingin mengubah logo. Format: JPG, PNG, WEBP. Max: 2MB.</div>
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.car-brands.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
