<?php

namespace App\Filament\Resources\ExpenseCategories;

use App\Filament\Resources\ExpenseCategories\Pages;
use App\Filament\Resources\ExpenseCategories\Schemas\ExpenseCategoryForm;
use App\Filament\Resources\ExpenseCategories\Tables\ExpenseCategoriesTable;
use App\Models\ExpenseCategory;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class ExpenseCategoryResource extends Resource
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = ExpenseCategory::class;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?string $modelLabel = 'Kategori Biaya';

    protected static ?string $pluralModelLabel = 'Kategori Biaya';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return ExpenseCategoryForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return ExpenseCategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenseCategories::route('/'),
        ];
    }
}
