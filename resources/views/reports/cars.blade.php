<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kendaraan Siliwangi Rental</title>
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
        <h2>LAPORAN DATA KENDARAAN</h2>
        <p>Siliwangi Rental - Armada Terintegrasi</p>
        <p>Tanggal Cetak: {{ date('d F Y H:i') }}</p>
    </div>
 
    <table>
        <thead>
            <tr>
                <th>Nama Mobil</th>
                <th>No. Plat</th>
                <th>Brand</th>
                <th>Tipe</th>
                <th>Cabang</th>
                <th>Harga Sewa</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $record->car_name }}</td>
                <td>{{ $record->plate_number }}</td>
                <td>{{ $record->brand_name ?? '-' }}</td>
                <td>{{ $record->type_name ?? '-' }}</td>
                <td>{{ $record->branch->name ?? 'Utama' }}</td>
                <td>Rp {{ number_format($record->daily_price, 0, ',', '.') }}</td>
                <td>{{ strtoupper($record->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
 
    <div class="footer">
        <p>Dicetak secara otomatis oleh Sistem Siliwangi Rental</p>
    </div>
</body>
</html>
