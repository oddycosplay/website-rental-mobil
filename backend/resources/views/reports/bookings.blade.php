<!DOCTYPE html>
<html>
<head>
    <title>Laporan Booking Siliwangi Rental</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { text-align: right; margin-top: 30px; font-size: 8pt; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN DATA BOOKING</h2>
        <p>Siliwangi Rental - Cabang Terintegrasi</p>
        <p>Tanggal Cetak: {{ date('d F Y H:i') }}</p>
    </div>
 
    <table>
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Mobil</th>
                <th>Cabang</th>
                <th>Tgl Ambil</th>
                <th>Tgl Kembali</th>
                <th>Durasi</th>
                <th>Total Bayar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $record->booking_code }}</td>
                <td>{{ $record->customer->name ?? '-' }}</td>
                <td>{{ $record->car->car_name ?? '-' }}</td>
                <td>{{ $record->branch->name ?? '-' }}</td>
                <td>{{ $record->pickup_date }}</td>
                <td>{{ $record->return_date }}</td>
                <td>{{ $record->total_day }} Hari</td>
                <td>Rp {{ number_format($record->grand_total, 0, ',', '.') }}</td>
                <td>{{ strtoupper($record->booking_status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
 
    <div class="footer">
        <p>Dicetak secara otomatis oleh Sistem Siliwangi Rental</p>
    </div>
</body>
</html>
