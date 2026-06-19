<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages;
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use App\Filament\Resources\Customers\Schemas\CustomerInfolist;
use App\Filament\Resources\Customers\Tables\CustomersTable;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerResource extends Resource
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = \App\Models\User::class;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->role('customer');
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('view_any_customer') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('create_customer') ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->can('update_customer') ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->can('delete_customer') ?? false;
    }

    public static function canView(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->can('view_customer') ?? false;
    }

    protected static ?string $navigationLabel = 'Pelanggan';

    public static function getNavigationLabel(): string
    {
        return 'Database Pelanggan';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Database Pelanggan';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-user-group';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Daftar Aset';
    }

    public static function form(Form $form): Form
    {
        return CustomerForm::configure($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return CustomerInfolist::configure($infolist);
    }

    public static function table(Table $table): Table
    {
        return CustomersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
