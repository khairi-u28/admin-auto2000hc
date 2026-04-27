<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AreaResource\Pages\CreateArea;
use App\Filament\Resources\AreaResource\Pages\EditArea;
use App\Filament\Resources\AreaResource\Pages\ListAreas;
use App\Filament\Resources\AreaResource\Pages\ViewArea;
use App\Filament\Resources\AreaResource\RelationManagers\BranchesRelationManager;
use App\Models\Area;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AreaResource extends Resource
{
    protected static ?string $model = Area::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Area';

    protected static ?string $pluralModelLabel = 'Areas';

    public static function getNavigationGroup(): ?string
    {
        return 'Master Data';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Area')
                    ->columns(2)
                    ->components([
                        Select::make('region_id')
                            ->label('Region')
                            ->relationship('region', 'nama_region')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('nama_area')
                            ->label('Nama Area')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('nama_abh')
                            ->label('Nama ABH'),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil Area')
                    ->columns(2)
                    ->components([
                        TextEntry::make('nama_area')
                            ->label('Nama Area'),
                        TextEntry::make('region.nama_region')
                            ->label('Region'),
                        TextEntry::make('nama_abh')
                            ->label('Nama ABH')
                            ->placeholder('-'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with(['region'])->withCount('branches'))
            ->columns([
                TextColumn::make('nama_area')
                    ->label('Nama Area')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('region.nama_region')
                    ->label('Region')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_abh')
                    ->label('Nama ABH')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('branches_count')
                    ->label('Jml. Cabang')
                    ->sortable(),
            ])
            ->recordUrl(fn (Area $record): string => static::getUrl('view', ['record' => $record]))
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\CreateAction::make(),
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BranchesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAreas::route('/'),
            'create' => CreateArea::route('/create'),
            'view' => ViewArea::route('/{record}'),
            'edit' => EditArea::route('/{record}/edit'),
        ];
    }
}
