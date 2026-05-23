<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan Siliwangi Rental</title>
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
        <h2>LAPORAN KEUANGAN TAHUN {{ $year }}</h2>
        <p>Siliwangi Rental - Cabang Terintegrasi</p>
        <p>Tanggal Cetak: {{ date('d F Y H:i') }}</p>
    </div>
 
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Pendapatan</th>
                <th>Pengeluaran</th>
                <th class="text-right">Laba/Rugi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report_data as $data)
            <tr>
                <td>{{ $data['month'] }}</td>
                <td>Rp {{ number_format($data['income'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($data['expense'], 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($data['profit'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f9f9f9;">
                <td>TOTAL</td>
                <td>Rp {{ number_format($total_income, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($total_expense, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($total_profit, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
 
    <div class="footer">
        <p>Dicetak secara otomatis oleh Sistem Siliwangi Rental</p>
    </div>
</body>
</html>
