<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Models\Payment;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentExportAction
{
    public static function make(): Action
    {
        return Action::make('export_payments')
            ->label('Ekspor Excel (CSV)')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('success')
            ->action(fn () => static::export(Payment::with(['booking.customer'])->get()));
    }

    public static function makeBulk(): BulkAction
    {
        return BulkAction::make('exportSelectedPayments')
            ->label('Ekspor Terpilih (CSV)')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(fn (Collection $records) => static::export($records));
    }

    protected static function export(Collection $records): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="Laporan_Pembayaran_Siliwangi_' . date('Y-m-d_H-i') . '.csv"',
        ];

        return new StreamedResponse(function () use ($records) {
            $handle = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 support
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header Row
            fputcsv($handle, [
                'Tgl Bayar',
                'Kode Transaksi',
                'ID Booking',
                'Pelanggan',
                'Metode',
                'Tagihan',
                'Dibayar',
                'Status',
            ]);

            foreach ($records as $record) {
                fputcsv($handle, [
                    $record->payment_date ? $record->payment_date->format('d/m/Y H:i') : '-',
                    $record->payment_code,
                    $record->booking->booking_code ?? '-',
                    $record->booking->customer->name ?? '-',
                    strtoupper($record->payment_method ?? 'MIDTRANS'),
                    'Rp ' . number_format($record->gross_amount, 0, ',', '.'),
                    'Rp ' . number_format($record->paid_amount, 0, ',', '.'),
                    strtoupper($record->payment_status),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
