<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class FineReport extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static string|null $navigationLabel = 'Laporan Denda';
    protected static string|null $title = 'Laporan Denda Keterlambatan';
    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';
 
    protected static string $view = 'filament.pages.fine-report';
 
    /** @var \Illuminate\Support\Collection */
    public $fines;
 
    public function mount(): void
    {
        $this->fines = \App\Models\Booking::with(['customer', 'car', 'branch'])
            ->where('late_fee', '>', 0)
            ->latest()
            ->get();
    }
 
    public function exportExcel()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\FinesExport(), 'Laporan_Denda_' . date('Y-m-d') . '.xlsx');
    }
 
    public function exportPdf()
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.fines', ['records' => $this->fines])
            ->setPaper('a4', 'portrait');
        
        return response()->streamDownload(fn () => print($pdf->output()), 'Laporan_Denda_' . date('Y-m-d') . '.pdf');
    }
}
