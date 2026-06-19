<!DOCTYPE html>
<html>
<head>
    <title>Laporan Denda Siliwangi Rental</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { text-align: right; margin-top: 30px; font-size: 8pt; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN DENDA KETERLAMBATAN</h2>
        <p>Siliwangi Rental - Administrasi & Kepatuhan</p>
        <p>Tanggal Cetak: {{ date('d F Y H:i') }}</p>
    </div>
 
    <table>
        <thead>
            <tr>
                <th>Booking Code</th>
                <th>Pelanggan</th>
                <th>Mobil</th>
                <th>Tgl Kembali</th>
                <th class="text-right">Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $record->booking_code }}</td>
                <td>{{ $record->customer->name ?? '-' }}</td>
                <td>{{ $record->car->car_name ?? '-' }}</td>
                <td>{{ $record->return_date->format('d/m/Y H:i') }}</td>
                <td class="text-right">Rp {{ number_format($record->late_fee, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f9f9f9;">
                <td colspan="4" class="text-right">TOTAL DENDA</td>
                <td class="text-right">Rp {{ number_format(collect($records)->sum('late_fee'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
 
    <div class="footer">
        <p>Dicetak secara otomatis oleh Sistem Siliwangi Rental</p>
    </div>
</body>
</html>
