<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;

class PaymentForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pembayaran')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('payment_code')
                                    ->label('Kode Pembayaran')
                                    ->disabled(),
                                Forms\Components\TextInput::make('transaction_id')
                                    ->label('ID Transaksi')
                                    ->disabled(),
                            ]),
                        
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'success' => 'Berhasil',
                                'failed' => 'Gagal',
                                'expired' => 'Kadaluarsa',
                            ])
                            ->required(),
                        
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('gross_amount')
                                    ->label('Tagihan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled(),
                                Forms\Components\TextInput::make('paid_amount')
                                    ->label('Jumlah Dibayar')
                                    ->numeric()
                                    ->prefix('Rp'),
                            ]),
                        
                        Forms\Components\DateTimePicker::make('payment_date')
                            ->label('Tanggal Bayar'),
                    ]),
            ]);
    }
}
