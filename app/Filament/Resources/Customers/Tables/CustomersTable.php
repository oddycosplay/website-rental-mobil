<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->icon('heroicon-m-user')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->icon('heroicon-m-phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.nik')
                    ->label('No. KTP')
                    ->icon('heroicon-m-identification')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.pekerjaan')
                    ->label('Pekerjaan')
                    ->icon('heroicon-m-briefcase')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.customer_status')
                    ->label('Status Verifikasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\ImageColumn::make('customer.ktp_image')
                    ->label('Foto KTP')
                    ->circular()
                    ->disk('public'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->icon('heroicon-m-envelope')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
