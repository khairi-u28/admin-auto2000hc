<?php

namespace App\Filament\Resources\DevelopmentPrograms;

use App\Filament\Resources\DevelopmentPrograms\Pages\CreateDevelopmentProgram;
use App\Filament\Resources\DevelopmentPrograms\Pages\EditDevelopmentProgram;
use App\Filament\Resources\DevelopmentPrograms\Pages\ListDevelopmentPrograms;
use App\Filament\Resources\DevelopmentPrograms\Schemas\DevelopmentProgramForm;
use App\Filament\Resources\DevelopmentPrograms\Tables\DevelopmentProgramsTable;
use App\Models\DevelopmentProgram;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DevelopmentProgramResource extends Resource
{
    protected static ?string $model = DevelopmentProgram::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?int $navigationSort = 6;

    protected static ?string $modelLabel = 'Program Pengembangan';

    protected static ?string $pluralModelLabel = 'Program Pengembangan';

    public static function getNavigationGroup(): ?string
    {
        return 'Enrollment & Operasional';
    }

    public static function form(Schema $schema): Schema
    {
        return DevelopmentProgramForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DevelopmentProgramsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListDevelopmentPrograms::route('/'),
            'create' => CreateDevelopmentProgram::route('/create'),
            'edit'   => EditDevelopmentProgram::route('/{record}/edit'),
        ];
    }
}
