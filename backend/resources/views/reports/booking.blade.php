<!DOCTYPE html>
<html>
<head>
    <title>Laporan Booking Siliwangi Rental</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #EAB308;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #111;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .summary-table th, .summary-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .summary-table th {
            background-color: #f9f9f9;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .data-table th {
            background-color: #f9f9f9;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SILIWANGI RENTAL</h1>
        <p>Laporan Booking Kendaraan</p>
        <p>
            Periode: 
            @if($filter_month === 'all')
                Tahun {{ $filter_year }}
            @else
                Bulan {{ now()->month((int)$filter_month)->format('F') }} {{ $filter_year }}
            @endif
        </p>
    </div>

    <div class="summary">
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Total Booking</th>
                    <th>Booking Selesai</th>
                    <th>Booking Batal</th>
                    <th>Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $summary['total_bookings'] }}</td>
                    <td>{{ $summary['completed_bookings'] }}</td>
                    <td>{{ $summary['cancelled_bookings'] }}</td>
                    <td>Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>{{ $filter_month === 'all' ? 'Bulan' : 'Tanggal' }}</th>
                <th class="text-center">Jumlah Booking</th>
                <th class="text-right">Pendapatan (Paid)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report_data as $data)
                <tr>
                    <td>{{ $filter_month === 'all' ? $data['month'] : $data['date'] }}</td>
                    <td class="text-center">{{ $data['total_bookings'] }}</td>
                    <td class="text-right">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>TOTAL</td>
                <td class="text-center">{{ collect($report_data)->sum('total_bookings') }}</td>
                <td class="text-right">Rp {{ number_format(collect($report_data)->sum('revenue'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
