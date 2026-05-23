@extends('layouts.sbadmin')

@section('title', 'Analitik Bisnis Lanjutan')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Analitik Bisnis Lanjutan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Analitik Bisnis</li>
    </ol>

    <div class="row">
        <!-- Car Utilization Rate -->
        <div class="col-xl-6">
            <div class="card mb-4 shadow border-0 rounded-3">
                <div class="card-header bg-slate-900 text-white font-weight-bold">
                    <i class="fas fa-chart-bar me-1"></i> Utilitas Kendaraan (30 Hari Terakhir)
                </div>
                <div class="card-body">
                    <canvas id="utilizationChart" width="100%" height="50"></canvas>
                    <div class="mt-4">
                        @foreach($utilizationData as $car)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-bold text-slate-700">{{ $car['name'] }}</span>
                            <div class="progress flex-grow-1 mx-3" style="height: 8px;">
                                <div class="progress-bar bg-gold" role="progressbar" style="width: {{ $car['rate'] }}%" aria-valuenow="{{ $car['rate'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="small font-black text-slate-900">{{ $car['rate'] }}%</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Profit vs Loss -->
        <div class="col-xl-6">
            <div class="card mb-4 shadow border-0 rounded-3">
                <div class="card-header bg-slate-900 text-white font-weight-bold">
                    <i class="fas fa-chart-area me-1"></i> Profit vs Loss (6 Bulan Terakhir)
                </div>
                <div class="card-body">
                    <canvas id="profitChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Customers -->
        <div class="col-xl-12">
            <div class="card mb-4 shadow border-0 rounded-3">
                <div class="card-header bg-slate-900 text-white font-weight-bold">
                    <i class="fas fa-crown me-1 text-gold"></i> Top Customers (Lifetime Value)
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Rank</th>
                                    <th>Customer</th>
                                    <th>Total Booking</th>
                                    <th class="text-end px-4">Total Spending</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCustomers as $index => $customer)
                                <tr>
                                    <td class="px-4 fw-bold">#{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $customer->name }}</div>
                                        <div class="small text-muted">{{ $customer->phone }}</div>
                                    </td>
                                    <td>{{ $customer->bookings_count ?? $customer->bookings()->count() }} Pesanan</td>
                                    <td class="text-end px-4 font-black text-success">
                                        Rp {{ number_format($customer->bookings_sum_grand_total, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Utilization Chart
    var ctxUtil = document.getElementById("utilizationChart");
    new Chart(ctxUtil, {
        type: 'horizontalBar',
        data: {
            labels: {!! json_encode($utilizationData->pluck('name')) !!},
            datasets: [{
                label: "Utilization Rate (%)",
                backgroundColor: "rgba(212, 175, 55, 0.8)",
                borderColor: "rgba(212, 175, 55, 1)",
                data: {!! json_encode($utilizationData->pluck('rate')) !!},
            }],
        },
        options: {
            scales: {
                xAxes: [{
                    ticks: { min: 0, max: 100 },
                    gridLines: { display: false }
                }],
                yAxes: [{ gridLines: { display: true } }]
            },
            legend: { display: false }
        }
    });

    // Profit Chart
    var ctxProfit = document.getElementById("profitChart");
    new Chart(ctxProfit, {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($profitData)->pluck('month')) !!},
            datasets: [{
                label: "Income",
                borderColor: "rgba(16, 185, 129, 1)",
                backgroundColor: "rgba(16, 185, 129, 0.1)",
                data: {!! json_encode(collect($profitData)->pluck('income')) !!},
                fill: true
            }, {
                label: "Expense",
                borderColor: "rgba(239, 68, 68, 1)",
                backgroundColor: "rgba(239, 68, 68, 0.1)",
                data: {!! json_encode(collect($profitData)->pluck('expense')) !!},
                fill: true
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }]
            }
        }
    });
</script>
@endpush
