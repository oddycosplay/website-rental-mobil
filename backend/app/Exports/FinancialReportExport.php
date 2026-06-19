<?php
 
namespace App\Exports;
 
use Maatwebsite\Excel\Concerns\FromCollection as ExcelFromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings as ExcelWithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize as ExcelShouldAutoSize;
 
class FinancialReportExport implements ExcelFromCollection, ExcelWithHeadings, ExcelShouldAutoSize
{
    protected array $data;
    protected int $year;
 
    public function __construct(array $data, int $year)
    {
        $this->data = $data;
        $this->year = $year;
    }
 
    public function collection(): \Illuminate\Support\Collection
    {
        return collect($this->data)->map(function ($row) {
            return [
                $row['month'],
                $row['income'],
                $row['expense'],
                $row['profit'],
            ];
        });
    }
 
    public function headings(): array
    {
        return [
            ['LAPORAN KEUANGAN TAHUN ' . $this->year],
            ['Bulan', 'Pendapatan (Rp)', 'Pengeluaran (Rp)', 'Laba/Rugi (Rp)']
        ];
    }
}
