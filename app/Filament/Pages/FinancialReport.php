<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Booking;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class FinancialReport extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'filament.pages.financial-report';

    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Laporan Keuangan';

    protected static ?string $title = 'Laporan Keuangan';

    public int $filter_year;
    public array $report_data = [];

    public static function shouldRegisterNavigation(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->hasRole(['super-admin', 'owner', 'finance', 'admin', 'operasional']) ?? true;
    }

    public function mount(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        abort_unless($user?->hasRole(['super-admin', 'owner', 'finance', 'admin', 'operasional']) ?? true, 403);
        $this->filter_year = (int) now()->year;
        $this->generateReport();
    }

    public function generateReport()
    {
        $income = Booking::where('payment_status', 'paid')
            ->whereYear('created_at', $this->filter_year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(grand_total) as total'))
            ->groupBy('month')
            ->pluck('total', 'month');

        $expense = Expense::whereYear('date', $this->filter_year)
            ->select(DB::raw('MONTH(date) as month'), DB::raw('SUM(amount) as total'))
            ->groupBy('month')
            ->pluck('total', 'month');

        $this->report_data = [];
        foreach (range(1, 12) as $m) {
            $inc = $income[$m] ?? 0;
            $exp = $expense[$m] ?? 0;
            $this->report_data[] = [
                'month' => now()->month($m)->format('F'),
                'income' => $inc,
                'expense' => $exp,
                'profit' => $inc - $exp,
            ];
        }
    }
    public function exportExcel()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\FinancialReportExport($this->report_data, $this->filter_year),
            "Laporan_Keuangan_{$this->filter_year}.xlsx"
        );
    }
 
    public function exportPdf()
    {
        $pdf = Pdf::loadView('reports.financial', [
            'report_data' => $this->report_data,
            'year' => $this->filter_year,
            'total_income' => collect($this->report_data)->sum('income'),
            'total_expense' => collect($this->report_data)->sum('expense'),
            'total_profit' => collect($this->report_data)->sum('profit'),
        ]);
 
        return response()->streamDownload(fn () => print($pdf->output()), "Laporan_Keuangan_{$this->filter_year}.pdf");
    }
}
