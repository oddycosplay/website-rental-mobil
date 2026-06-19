<?php
 
namespace App\Filament\Resources\Drivers\Tables;
 
use App\Models\Driver;
use App\Exports\DriversExport;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
 
class DriverExportAction
{
    public static function make(): \Filament\Tables\Actions\Action
    {
        return \Filament\Tables\Actions\Action::make('export_drivers_excel')
            ->label('Ekspor Excel')
            ->icon('heroicon-o-document-text')
            ->color('success')
            ->action(fn () => \Maatwebsite\Excel\Facades\Excel::download(new DriversExport(), 'Laporan_Driver_' . date('Y-m-d') . '.xlsx'));
    }
 
    public static function makePdf(): \Filament\Tables\Actions\Action
    {
        return \Filament\Tables\Actions\Action::make('export_drivers_pdf')
            ->label('Ekspor PDF')
            ->icon('heroicon-o-document-arrow-down')
            ->color('danger')
            ->action(function () {
                $records = Driver::all();
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.drivers', ['records' => $records])
                    ->setPaper('a4', 'portrait');
                
                return response()->streamDownload(fn () => print($pdf->output()), 'Laporan_Driver_' . date('Y-m-d') . '.pdf');
            });
    }
 
    public static function makeBulkExcel(): \Filament\Tables\Actions\BulkAction
    {
        return \Filament\Tables\Actions\BulkAction::make('exportSelectedDriversExcel')
            ->label('Ekspor Excel (Terpilih)')
            ->icon('heroicon-o-document-text')
            ->color('success')
            ->action(fn (Collection $records) => \Maatwebsite\Excel\Facades\Excel::download(new DriversExport($records), 'Laporan_Driver_Terpilih.xlsx'));
    }
 
    public static function makeBulkPdf(): \Filament\Tables\Actions\BulkAction
    {
        return \Filament\Tables\Actions\BulkAction::make('exportSelectedDriversPdf')
            ->label('Ekspor PDF (Terpilih)')
            ->icon('heroicon-o-document-arrow-down')
            ->color('danger')
            ->action(function (Collection $records) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.drivers', ['records' => $records])
                    ->setPaper('a4', 'portrait');
                
                return response()->streamDownload(fn () => print($pdf->output()), 'Laporan_Driver_Terpilih.pdf');
            });
    }
}
