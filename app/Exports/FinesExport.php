<?php
 
namespace App\Exports;
 
use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection as ExcelFromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings as ExcelWithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping as ExcelWithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize as ExcelShouldAutoSize;
 
class FinesExport implements ExcelFromCollection, ExcelWithHeadings, ExcelWithMapping, ExcelShouldAutoSize
{
    public function collection(): \Illuminate\Support\Collection
    {
        return Booking::with(['customer', 'car', 'branch'])
            ->where('late_fee', '>', 0)
            ->latest()
            ->get();
    }
 
    public function headings(): array
    {
        return [
            'ID Pesanan',
            'Pelanggan',
            'Mobil',
            'Tgl Kembali Seharusnya',
            'Tgl Kembali Aktual',
            'Denda (Rp)',
            'Status Bayar',
        ];
    }
 
    public function map($booking): array
    {
        return [
            $booking->booking_code,
            $booking->customer->name ?? '-',
            $booking->car->car_name ?? '-',
            $booking->return_date->format('d/m/Y H:i'),
            $booking->updated_at->format('d/m/Y H:i'), // Assuming updated_at is when it was completed
            $booking->late_fee,
            strtoupper($booking->payment_status),
        ];
    }
}
