<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected \Illuminate\Support\Collection $data;
    protected string $filter_month;
    protected int $filter_year;

    public function __construct(array $data, string $filter_month, int $filter_year)
    {
        $this->data = collect($data);
        $this->filter_month = $filter_month;
        $this->filter_year = $filter_year;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        if ($this->filter_month === 'all') {
            return [
                'Bulan',
                'Jumlah Booking',
                'Pendapatan (Paid)'
            ];
        }

        return [
            'Tanggal',
            'Jumlah Booking',
            'Pendapatan (Paid)'
        ];
    }

    public function map($row): array
    {
        if ($this->filter_month === 'all') {
            return [
                $row['month'],
                $row['total_bookings'],
                $row['revenue'],
            ];
        }

        return [
            $row['date'],
            $row['total_bookings'],
            $row['revenue'],
        ];
    }

    public function title(): string
    {
        if ($this->filter_month === 'all') {
            return "Booking Tahun " . $this->filter_year;
        }
        return "Booking " . now()->month((int)$this->filter_month)->format('F') . " " . $this->filter_year;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
