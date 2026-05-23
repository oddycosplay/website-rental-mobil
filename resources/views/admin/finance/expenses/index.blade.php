@extends('layouts.sbadmin')

@section('title', 'Data Pengeluaran')

@section('content')
<h1 class="mt-4">Data Pengeluaran</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pengeluaran</li>
</ol>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="fas fa-table me-1"></i>
            Daftar Pengeluaran
        </div>
        <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Pengeluaran
        </a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <table id="datatablesSimple" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Cabang</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $expense)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $expense->date->format('d M Y') }}</td>
                    <td><span class="badge bg-secondary">{{ $expense->category->name }}</span></td>
                    <td>{{ $expense->branch->name ?? '-' }}</td>
                    <td class="text-danger fw-bold">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                    <td>{{ $expense->description }}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            @if($expense->attachment)
                                <a href="{{ Storage::url($expense->attachment) }}" target="_blank" class="btn btn-action btn-info" title="Lihat Lampiran">
                                    <i class="fas fa-paperclip"></i>
                                </a>
                            @endif
                            <a href="{{ route('admin.expenses.edit', $expense->id) }}" class="btn btn-action btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.expenses.destroy', $expense->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-danger" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
