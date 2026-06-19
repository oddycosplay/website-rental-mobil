<?php
/** Export Booking Records to Excel */

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection as ExcelFromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings as ExcelWithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping as ExcelWithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize as ExcelShouldAutoSize;

class BookingsExport implements ExcelFromCollection, ExcelWithHeadings, ExcelWithMapping, ExcelShouldAutoSize
{
    protected ?\Illuminate\Support\Collection $records;

    public function __construct($records = null)
    {
        $this->records = $records;
    }

    public function collection()
    {
        return $this->records ?: Booking::all();
    }

    public function headings(): array
    {
        return [
            'ID Pesanan',
            'Pelanggan',
            'No. WhatsApp',
            'Mobil',
            'Cabang',
            'Tgl Ambil',
            'Tgl Kembali',
            'Durasi (Hari)',
            'Total Bayar (Rp)',
            'Status Pembayaran',
            'Status Booking',
        ];
    }

    public function map($booking): array
    {
        return [
            $booking->booking_code,
            $booking->customer->name ?? '-',
            $booking->customer->phone ?? '-',
            $booking->car->car_name ?? '-',
            $booking->branch->name ?? 'Cabang Utama',
            $booking->pickup_date,
            $booking->return_date,
            $booking->total_day,
            $booking->grand_total,
            strtoupper($booking->payment_status),
            strtoupper($booking->booking_status),
        ];
    }
}
