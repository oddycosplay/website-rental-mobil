@extends('layouts.admin')

@section('title', 'Payment Simulator - Siliwangi Admin')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Midtrans Simulator</h1>
        <div class="breadcrumb">Keuangan <i class="fas fa-chevron-right mx-2"></i> Simulator</div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Simulasi Callback</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.finance.simulate.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Pilih Booking</label>
                        <select name="booking_code" class="form-control" required>
                            @foreach($bookings as $b)
                                <option value="{{ $b->booking_code }}">{{ $b->booking_code }} - {{ $b->customer->name }} (Rp {{ number_format($b->grand_total) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Simulasi</label>
                        <select name="status" class="form-control" required>
                            <option value="settlement">Settlement (Success)</option>
                            <option value="pending">Pending</option>
                            <option value="expire">Expire</option>
                            <option value="cancel">Cancel</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-gold w-100">Kirim Callback Simulasi</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Petunjuk</h5>
            </div>
            <div class="card-body">
                <p>Gunakan simulator ini untuk menguji apakah sistem berhasil:</p>
                <ul>
                    <li>Mencatat <strong>PaymentLog</strong> baru.</li>
                    <li>Mengupdate status <strong>Booking</strong> menjadi 'confirmed' jika settlement.</li>
                    <li>Mengupdate status <strong>Payment</strong> menjadi 'paid' jika settlement.</li>
                </ul>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Simulator ini melewati pengecekan CSRF dan memalsukan signature key sesuai Server Key Anda.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
