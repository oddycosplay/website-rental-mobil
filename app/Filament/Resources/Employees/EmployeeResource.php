<?php

namespace App\Filament\Resources\Employees;

use App\Filament\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Resources\Employees\Pages\EditEmployee;
use App\Filament\Resources\Employees\Pages\ListEmployees;
use App\Filament\Resources\Employees\Pages\ViewEmployee;
use App\Filament\Resources\Employees\Schemas\EmployeeForm;
use App\Filament\Resources\Employees\Tables\EmployeesTable;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = Employee::class;

    protected static ?string $slug = 'employees';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Data Karyawan';

    protected static ?string $pluralLabel = 'Data Karyawan';

    protected static ?string $modelLabel = 'Karyawan';

    protected static ?string $navigationGroup = 'Karyawan';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canAccess(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user?->hasAnyRole(['super-admin', 'owner']) ?? false;
    }

    public static function form(Form $form): Form
    {
        return EmployeeForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return EmployeesTable::configure($table);
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
            'index' => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'view' => ViewEmployee::route('/{record}'),
            'edit' => EditEmployee::route('/{record}/edit'),
        ];
    }
}
