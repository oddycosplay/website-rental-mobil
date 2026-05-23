<?php

namespace App\Filament\Resources\Expenses\Tables;

use App\Models\Expense;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExpenseExportAction
{
    public static function make(): Action
    {
        return Action::make('export_expenses')
            ->label('Ekspor Excel (CSV)')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->action(fn () => static::export(Expense::with(['branch'])->get()));
    }

    public static function makeBulk(): BulkAction
    {
        return BulkAction::make('exportSelectedExpenses')
            ->label('Ekspor Terpilih (CSV)')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(fn (Collection $records) => static::export($records));
    }

    protected static function export(Collection $records): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Laporan_Pengeluaran_Siliwangi_' . date('Y-m-d_H-i') . '.csv"',
        ];

        return new StreamedResponse(function () use ($records) {
            $handle = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 support
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header Row
            fputcsv($handle, [
                'Tanggal',
                'Kategori',
                'Cabang',
                'Jumlah (Amount)',
                'Keterangan',
            ]);

            foreach ($records as $record) {
                fputcsv($handle, [
                    $record->date->format('d/m/Y'),
                    $record->category->name ?? '-',
                    $record->branch->name ?? '-',
                    'Rp ' . number_format($record->amount, 0, ',', '.'),
                    $record->description ?? '-',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
