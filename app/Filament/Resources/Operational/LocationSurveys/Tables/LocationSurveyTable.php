<?php

namespace App\Filament\Resources\Operational\LocationSurveys\Tables;

use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;

class LocationSurveyTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking.booking_code')
                    ->label('Kode Booking')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('booking.guest_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->description(fn ($record) => $record->booking?->guest_phone ?? '-'),

                Tables\Columns\TextColumn::make('surveyor_name')
                    ->label('Surveyor / Petugas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('survey_date')
                    ->label('Tgl Survey')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('recommendation')
                    ->label('Rekomendasi')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'layak'                => 'Layak',
                        'layak_dengan_catatan' => 'Layak dg Catatan',
                        'tidak_layak'          => 'Tidak Layak',
                        default                => '-',
                    })
                    ->color(fn (?string $state) => match ($state) {
                        'layak'                => 'success',
                        'layak_dengan_catatan' => 'warning',
                        'tidak_layak'          => 'danger',
                        default                => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'draft'     => 'Draft',
                        'submitted' => 'Menunggu Approve',
                        'approved'  => 'Disetujui',
                        'rejected'  => 'Ditolak',
                        default     => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'draft'     => 'gray',
                        'submitted' => 'warning',
                        'approved'  => 'success',
                        'rejected'  => 'danger',
                        default     => 'gray',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft'     => 'Draft',
                        'submitted' => 'Menunggu Approve',
                        'approved'  => 'Disetujui',
                        'rejected'  => 'Ditolak',
                    ]),

                Tables\Filters\SelectFilter::make('recommendation')
                    ->label('Rekomendasi')
                    ->options([
                        'layak'                => 'Layak',
                        'layak_dengan_catatan' => 'Layak dg Catatan',
                        'tidak_layak'          => 'Tidak Layak',
                    ]),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'submitted')
                    ->action(function ($record) {
                        // If recommendation is 'tidak_layak', auto-reject and blacklist instead
                        if ($record->recommendation === 'tidak_layak') {
                            $record->update([
                                'status' => 'rejected',
                                'approved_by' => auth()->id(),
                                'approved_at' => now(),
                            ]);

                            if ($record->booking && $record->booking->customer) {
                                $record->booking->customer->update([
                                    'customer_status' => 'blacklist',
                                    'is_active' => false,
                                ]);
                            }

                            if ($record->booking) {
                                $record->booking->update([
                                    'booking_status' => 'cancelled',
                                    'notes' => 'Survey ditolak karena renter dinilai TIDAK LAYAK. Akun di-blacklist dan proses refund dipicu otomatis.',
                                ]);
                            }

                            Notification::make()
                                ->title('Survey Ditolak secara otomatis')
                                ->body('Rekomendasi Renter tidak layak. Renter telah di-BLACKLIST dan Booking dibatalkan (Refund dipicu).')
                                ->danger()
                                ->send();
                            return;
                        }

                        $record->update([
                            'status'      => 'approved',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Survey disetujui')
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'submitted')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'rejected',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                        ]);

                        // Automatically blacklist customer and cancel booking if recommendation is 'tidak_layak'
                        if ($record->booking && $record->booking->customer) {
                            $record->booking->customer->update([
                                'customer_status' => 'blacklist',
                                'is_active' => false,
                            ]);
                        }

                        if ($record->booking) {
                            $record->booking->update([
                                'booking_status' => 'cancelled',
                                'notes' => 'Survey ditolak karena renter dinilai TIDAK LAYAK. Akun di-blacklist dan proses refund dipicu otomatis.',
                            ]);
                        }

                        Notification::make()
                            ->title('Survey ditolak')
                            ->body('Renter di-BLACKLIST & Booking dibatalkan (Proses refund dipicu).')
                            ->danger()
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-map-pin')
            ->emptyStateHeading('Belum ada data survey')
            ->emptyStateDescription('Survey kelayakan renter akan muncul di sini setelah tim operasional membuat laporan.');
    }
}
