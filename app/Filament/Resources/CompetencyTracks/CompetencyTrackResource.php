<?php

namespace App\Filament\Resources\CompetencyTracks;

use App\Filament\Resources\CompetencyTracks\Pages\CreateCompetencyTrack;
use App\Filament\Resources\CompetencyTracks\Pages\EditCompetencyTrack;
use App\Filament\Resources\CompetencyTracks\Pages\ListCompetencyTracks;
use App\Filament\Resources\CompetencyTracks\Schemas\CompetencyTrackForm;
use App\Filament\Resources\CompetencyTracks\Tables\CompetencyTracksTable;
use App\Models\CompetencyTrack;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompetencyTrackResource extends Resource
{
    protected static ?string $model = CompetencyTrack::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Track Kompetensi';

    protected static ?string $pluralModelLabel = 'Track Kompetensi';

    public static function getNavigationGroup(): ?string
    {
        return 'Konfigurasi';
    }

    public static function form(Schema $schema): Schema
    {
        return CompetencyTrackForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompetencyTracksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCompetencyTracks::route('/'),
            'create' => CreateCompetencyTrack::route('/create'),
            'edit'   => EditCompetencyTrack::route('/{record}/edit'),
        ];
    }
}
