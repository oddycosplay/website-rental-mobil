<?php

namespace App\Exports;

use App\Models\Car;
use Maatwebsite\Excel\Concerns\FromCollection as ExcelFromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings as ExcelWithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping as ExcelWithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize as ExcelShouldAutoSize;

class CarsExport implements ExcelFromCollection, ExcelWithHeadings, ExcelWithMapping, ExcelShouldAutoSize
{
    protected ?\Illuminate\Support\Collection $records;

    public function __construct(?\Illuminate\Support\Collection $records = null)
    {
        $this->records = $records;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        return $this->records ?: Car::with('branch')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Mobil',
            'No. Plat',
            'Brand',
            'Tipe',
            'Cabang',
            'Harga Sewa /Hari (Rp)',
            'Status',
            'Tersedia di Web',
        ];
    }

    public function map($car): array
    {
        return [
            $car->car_name,
            $car->plate_number,
            $car->brand_name ?? '-',
            $car->type_name ?? '-',
            $car->branch->name ?? 'Cabang Utama',
            $car->daily_price,
            strtoupper($car->status),
            $car->is_available ? 'YA' : 'TIDAK',
        ];
    }
}
