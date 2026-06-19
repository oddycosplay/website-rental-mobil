<?php
 
namespace App\Filament\Resources\Cars\Tables;
 
use App\Models\Car;
use App\Exports\CarsExport;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
 
class CarExportAction
{
    public static function make(): \Filament\Tables\Actions\Action
    {
        return \Filament\Tables\Actions\Action::make('export_cars_excel')
            ->label('Ekspor Excel')
            ->icon('heroicon-o-document-text')
            ->color('success')
            ->action(fn () => \Maatwebsite\Excel\Facades\Excel::download(new CarsExport(), 'Laporan_Kendaraan_' . date('Y-m-d') . '.xlsx'));
    }
 
    public static function makePdf(): \Filament\Tables\Actions\Action
    {
        return \Filament\Tables\Actions\Action::make('export_cars_pdf')
            ->label('Ekspor PDF')
            ->icon('heroicon-o-document-arrow-down')
            ->color('danger')
            ->action(function () {
                $records = Car::with(['branch'])->get();
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.cars', ['records' => $records])
                    ->setPaper('a4', 'landscape');
                
                return response()->streamDownload(fn () => print($pdf->output()), 'Laporan_Kendaraan_' . date('Y-m-d') . '.pdf');
            });
    }
 
    public static function makeBulkExcel(): \Filament\Tables\Actions\BulkAction
    {
        return \Filament\Tables\Actions\BulkAction::make('exportSelectedCarsExcel')
            ->label('Ekspor Excel (Terpilih)')
            ->icon('heroicon-o-document-text')
            ->color('success')
            ->action(fn (Collection $records) => \Maatwebsite\Excel\Facades\Excel::download(new CarsExport($records), 'Laporan_Kendaraan_Terpilih.xlsx'));
    }
 
    public static function makeBulkPdf(): \Filament\Tables\Actions\BulkAction
    {
        return \Filament\Tables\Actions\BulkAction::make('exportSelectedCarsPdf')
            ->label('Ekspor PDF (Terpilih)')
            ->icon('heroicon-o-document-arrow-down')
            ->color('danger')
            ->action(function (Collection $records) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.cars', ['records' => $records])
                    ->setPaper('a4', 'landscape');
                
                return response()->streamDownload(fn () => print($pdf->output()), 'Laporan_Kendaraan_Terpilih.pdf');
            });
    }
}
