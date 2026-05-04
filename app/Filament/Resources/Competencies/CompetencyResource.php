<?php

namespace App\Filament\Resources\Competencies;

use App\Filament\Resources\Competencies\Pages\CreateCompetency;
use App\Filament\Resources\Competencies\Pages\EditCompetency;
use App\Filament\Resources\Competencies\Pages\ListCompetencies;
use App\Filament\Resources\Competencies\RelationManagers\ModulesRelationManager;
use App\Filament\Resources\Competencies\Schemas\CompetencyForm;
use App\Filament\Resources\Competencies\Tables\CompetenciesTable;
use App\Models\Competency;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompetencyResource extends Resource
{
    protected static ?string $model = Competency::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Kompetensi';

    protected static ?string $pluralModelLabel = 'Kompetensi';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return 'Knowledge Management';
    }

    public static function form(Schema $schema): Schema
    {
        return CompetencyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompetenciesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ModulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCompetencies::route('/'),
            'create' => CreateCompetency::route('/create'),
            'edit'   => EditCompetency::route('/{record}/edit'),
        ];
    }
}
