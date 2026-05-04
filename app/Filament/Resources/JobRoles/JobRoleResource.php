<?php

namespace App\Filament\Resources\JobRoles;

use App\Filament\Resources\JobRoles\Pages\CreateJobRole;
use App\Filament\Resources\JobRoles\Pages\EditJobRole;
use App\Filament\Resources\JobRoles\Pages\ListJobRoles;
use App\Filament\Resources\JobRoles\Schemas\JobRoleForm;
use App\Filament\Resources\JobRoles\Tables\JobRolesTable;
use App\Filament\Resources\JobRoles\RelationManagers\CompetencyRequirementsRelationManager;
use App\Models\JobRole;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JobRoleResource extends Resource
{
    protected static ?string $model = JobRole::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'Jabatan';

    protected static ?string $pluralModelLabel = 'Jabatan';

    public static function getNavigationGroup(): ?string
    {
        return 'Master Data';
    }

    public static function form(Schema $schema): Schema
    {
        return JobRoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobRolesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CompetencyRequirementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListJobRoles::route('/'),
            'create' => CreateJobRole::route('/create'),
            'edit'   => EditJobRole::route('/{record}/edit'),
        ];
    }
}
