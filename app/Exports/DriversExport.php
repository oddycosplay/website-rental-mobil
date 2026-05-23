<?php
 
namespace App\Exports;
 
use App\Models\Driver;
use Maatwebsite\Excel\Concerns\FromCollection as ExcelFromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings as ExcelWithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping as ExcelWithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize as ExcelShouldAutoSize;
 
class DriversExport implements ExcelFromCollection, ExcelWithHeadings, ExcelWithMapping, ExcelShouldAutoSize
{
    protected ?\Illuminate\Support\Collection $records;
 
    public function __construct(?\Illuminate\Support\Collection $records = null)
    {
        $this->records = $records;
    }
 
    public function collection(): \Illuminate\Support\Collection
    {
        return $this->records ?: Driver::all();
    }
 
    public function headings(): array
    {
        return [
            'Nama Pengemudi',
            'Telepon',
            'No. SIM',
            'Alamat',
            'Status',
            'Sedang Bertugas',
        ];
    }
 
    public function map($driver): array
    {
        /** @var \App\Models\Driver $driver */
        return [
            $driver->name,
            $driver->phone,
            $driver->license_number,
            $driver->address,
            strtoupper($driver->status),
            $driver->is_busy ? 'YA' : 'TIDAK',
        ];
    }
}
