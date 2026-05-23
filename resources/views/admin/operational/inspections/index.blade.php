@extends('layouts.admin')

@section('title', 'Vehicle Inspections - Siliwangi Rental')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4 rounded-4" style="background: linear-gradient(135deg, #0F172A 0%, #1e293b 100%); color: white;">
                <h2 class="h4 fw-bold mb-1">Vehicle Inspection Hub</h2>
                <p class="mb-0 opacity-75">Monitor condition reports and manage check-in/check-out inspections.</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-5 text-center">
            <div class="mb-4">
                <i data-lucide="clipboard-check" class="text-primary opacity-25" style="width: 80px; height: 80px;"></i>
            </div>
            <h4 class="fw-bold text-main">Inspection Module coming soon</h4>
            <p class="text-muted mx-auto" style="max-width: 500px;">
                We are building a robust inspection system that allows you to upload photos and mark vehicle conditions during check-in and check-out.
            </p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">Back to Dashboard</a>
        </div>
    </div>
</div>
@endsection
