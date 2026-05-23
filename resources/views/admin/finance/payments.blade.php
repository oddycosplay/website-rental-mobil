@extends('layouts.sbadmin')

@section('title', 'Data Pemasukan')

@section('content')
<h1 class="mt-4">Data Pemasukan</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Pemasukan</li>
</ol>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Daftar Pembayaran (Income)
    </div>
    <div class="card-body">
        <table id="datatablesSimple" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Kode Bayar</th>
                    <th>Kode Booking</th>
                    <th>Pelanggan</th>
                    <th>Metode</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><span class="fw-bold">{{ $payment->payment_code }}</span></td>
                    <td><a href="{{ route('invoice', $payment->booking->booking_code) }}" target="_blank">{{ $payment->booking->booking_code }}</a></td>
                    <td>{{ $payment->booking->customer->name ?? '-' }}</td>
                    <td>{{ $payment->payment_method ?? 'Midtrans' }}</td>
                    <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') : '-' }}</td>
                    <td class="text-success fw-bold">Rp {{ number_format($payment->paid_amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge bg-{{ $payment->payment_status == 'success' ? 'success' : 'warning' }}">
                            {{ strtoupper($payment->payment_status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
