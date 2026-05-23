<?php

namespace App\Filament\Resources\Expenses\Tables;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\Expenses\Tables\ExpenseExportAction;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->icon('heroicon-m-calendar')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->icon('heroicon-m-tag')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('store.name')
                    ->label('Toko')
                    ->icon('heroicon-m-building-office')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah')
                    ->icon('heroicon-m-currency-dollar')
                    ->money('IDR')
                    ->color('danger')
                    ->weight('bold')
                    ->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Total')->money('IDR')),
                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->icon('heroicon-m-document-text')
                    ->limit(30)
                    ->toggleable(),
                Tables\Columns\ImageColumn::make('attachment')
                    ->label('Lampiran')
                    ->disk('public')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'Bensin/BBM' => 'Bensin/BBM',
                        'Pajak/STNK' => 'Pajak/STNK',
                        'Gaji Karyawan' => 'Gaji Karyawan',
                        'Servis Gedung' => 'Servis Gedung',
                        'Biaya Operasional' => 'Biaya Operasional',
                        'Lainnya' => 'Lainnya'
                    ]),
                Tables\Filters\SelectFilter::make('store_id')
                    ->label('Toko')
                    ->relationship('store', 'name'),
                Tables\Filters\Filter::make('date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')->label('Dari'),
                        \Filament\Forms\Components\DatePicker::make('until')->label('Hingga'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['from'], fn ($q) => $q->whereDate('date', '>=', $data['from']))
                        ->when($data['until'], fn ($q) => $q->whereDate('date', '<=', $data['until']))
                    ),
            ])
            ->headerActions([
                ExpenseExportAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    ExpenseExportAction::makeBulk(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
