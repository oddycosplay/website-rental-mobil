<?php

namespace App\Filament\Widgets;

use Filament\Facades\Filament;

use App\Models\Booking;
use App\Filament\Resources\Bookings\BookingResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestBookings extends BaseWidget
{
    protected static ?int $sort = 10;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->when(Filament::getTenant(), fn($q, $tenant) => $q->where('bookings.store_id', $tenant->id))
                    ->latest()
                    ->limit(6)
            )
            ->heading('Booking Terbaru')
            ->description('Daftar transaksi penyewaan mobil paling mutakhir.')
            ->headerActions([
                Tables\Actions\Action::make('view_all')
                    ->label('Lihat Semua')
                    ->url(fn() => route('filament.admin.resources.bookings.bookings.index'))
                    ->icon('heroicon-m-arrow-right')
                    ->size('sm')
                    ->color('gray'),
            ])
            ->columns([
                Tables\Columns\ImageColumn::make('car.thumbnail')
                    ->label('')
                    ->circular()
                    ->disk('public')
                    ->visibility('public'),

                Tables\Columns\TextColumn::make('booking_code')
                    ->label('ID Pesanan')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->description(fn($record) => $record->created_at->diffForHumans()),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->weight('semibold')
                    ->description(fn($record) => $record->customer->phone ?? '-'),

                Tables\Columns\TextColumn::make('car.car_name')
                    ->label('Mobil')
                    ->description(fn($record) => $record->car->plate_number),

                Tables\Columns\TextColumn::make('grand_total')
                    ->money('IDR')
                    ->label('Total')
                    ->color('success')
                    ->weight('bold')
                    ->alignment('right'),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Bayar')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        'partial' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('booking_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'ongoing' => 'primary',
                        'completed' => 'success',
                        'cancelled', 'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->icon('heroicon-m-eye')
                    ->iconButton()
                    ->url(fn($record) => route('filament.admin.resources.bookings.bookings.view', $record)),
            ]);
    }
}
