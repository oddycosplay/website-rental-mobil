<?php
 
namespace App\Filament\Resources\Bookings\Tables;
 
use App\Models\Booking;
use App\Exports\BookingsExport;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
 
class ExportAction
{
    public static function make(): \Filament\Tables\Actions\Action
    {
        return \Filament\Tables\Actions\Action::make('export_excel')
            ->label('Ekspor Excel')
            ->icon('heroicon-o-document-text')
            ->color('success')
            ->action(fn () => \Maatwebsite\Excel\Facades\Excel::download(new BookingsExport(), 'Laporan_Booking_' . date('Y-m-d') . '.xlsx'));
    }
 
    public static function makePdf(): \Filament\Tables\Actions\Action
    {
        return \Filament\Tables\Actions\Action::make('export_pdf')
            ->label('Ekspor PDF')
            ->icon('heroicon-o-document-arrow-down')
            ->color('danger')
            ->action(function () {
                $records = Booking::with(['customer', 'car', 'branch'])->latest()->get();
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.bookings', ['records' => $records])
                    ->setPaper('a4', 'landscape');
                
                return response()->streamDownload(fn () => print($pdf->output()), 'Laporan_Booking_' . date('Y-m-d') . '.pdf');
            });
    }
 
    public static function makeBulk(): \Filament\Tables\Actions\BulkAction
    {
        return \Filament\Tables\Actions\BulkAction::make('exportSelectedExcel')
            ->label('Ekspor Excel (Terpilih)')
            ->icon('heroicon-o-document-text')
            ->color('success')
            ->action(fn (Collection $records) => \Maatwebsite\Excel\Facades\Excel::download(new BookingsExport($records), 'Laporan_Booking_Terpilih.xlsx'));
    }
 
    public static function makeBulkPdf(): \Filament\Tables\Actions\BulkAction
    {
        return \Filament\Tables\Actions\BulkAction::make('exportSelectedPdf')
            ->label('Ekspor PDF (Terpilih)')
            ->icon('heroicon-o-document-arrow-down')
            ->color('danger')
            ->action(function (Collection $records) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.bookings', ['records' => $records])
                    ->setPaper('a4', 'landscape');
                
                return response()->streamDownload(fn () => print($pdf->output()), 'Laporan_Booking_Terpilih.pdf');
            });
    }
}
