<?php

namespace App\Filament\Resources\Stores;

use App\Filament\Resources\Stores\Pages\CreateStore;
use App\Filament\Resources\Stores\Pages\EditStore;
use App\Filament\Resources\Stores\Pages\ListStores;
use App\Filament\Resources\Stores\Pages\ViewStore;
use App\Filament\Resources\Stores\Schemas\StoreForm;
use App\Filament\Resources\Stores\Schemas\StoreInfolist;
use App\Filament\Resources\Stores\Tables\StoresTable;
use App\Models\Store;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class StoreResource extends Resource
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $recordTitleAttribute = 'Store';

    protected static ?string $navigationGroup = 'Sistem & Pengaturan';

    protected static ?string $navigationLabel = 'Kelola Toko (Cabang)';

    protected static ?string $pluralModelLabel = 'Kelola Toko (Cabang)';

    public static function form(Form $form): Form
    {
        return StoreForm::configure($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return StoreInfolist::configure($infolist);
    }

    public static function table(Table $table): Table
    {
        return StoresTable::configure($table);
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
            'index' => ListStores::route('/'),
            'create' => CreateStore::route('/create'),
            'view' => ViewStore::route('/{record}'),
            'edit' => EditStore::route('/{record}/edit'),
        ];
    }
}
