<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Set;
use Filament\Forms;

use App\Models\ExpenseCategory;
use App\Models\Store;

class ExpenseForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make('Detail Transaksi')
                            ->description('Catat rincian pengeluaran kas.')
                            ->icon('heroicon-m-banknotes')
                            ->schema([
                                Forms\Components\DatePicker::make('date')
                                    ->label('Tanggal')
                                    ->default(now())
                                    ->required()
                                    ->prefixIcon('heroicon-m-calendar'),
                                
                                Forms\Components\Select::make('category')
                                    ->label('Kategori')
                                    ->options([
                                        'Bensin/BBM' => 'Bensin/BBM',
                                        'Pajak/STNK' => 'Pajak/STNK',
                                        'Gaji Karyawan' => 'Gaji Karyawan',
                                        'Servis Gedung' => 'Servis Gedung',
                                        'Biaya Operasional' => 'Biaya Operasional',
                                        'Lainnya' => 'Lainnya'
                                    ])
                                    ->required()
                                    ->searchable()
                                    ->prefixIcon('heroicon-m-tag')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                    ])
                                    ->createOptionUsing(fn ($data) => $data['name']),

                                Forms\Components\Select::make('store_id')
                                    ->label('Toko')
                                    ->relationship('store', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->prefixIcon('heroicon-m-building-office'),

                                Forms\Components\TextInput::make('amount')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->prefixIcon('heroicon-m-currency-dollar'),
                            ])->columnSpan(2),

                        Section::make('Informasi Tambahan')
                            ->description('Detail pendukung transaksi.')
                            ->icon('heroicon-m-document-plus')
                            ->schema([
                                Forms\Components\Textarea::make('description')
                                    ->label('Keterangan')
                                    ->placeholder('Tujuan pengeluaran...')
                                    ->rows(3),
                                
                                Forms\Components\FileUpload::make('attachment')
                                    ->label('Lampiran/Nota')
                                    ->directory('expenses')
                                    ->image()
                                    ->imageEditor(),
                            ])->columnSpan(1),
                    ]),
            ]);
    }
}
