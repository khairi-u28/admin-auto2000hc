<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegionResource\Pages\CreateRegion;
use App\Filament\Resources\RegionResource\Pages\EditRegion;
use App\Filament\Resources\RegionResource\Pages\ListRegions;
use App\Filament\Resources\RegionResource\Pages\ViewRegion;
use App\Filament\Resources\RegionResource\RelationManagers\AreasRelationManager;
use App\Models\Employee;
use App\Models\Region;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use BackedEnum;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegionResource extends Resource
{
    protected static ?string $model = Region::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-globe-asia-australia';

    protected static ?string $navigationLabel = 'Region';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Region';

    protected static ?string $pluralModelLabel = 'Regions';

    public static function getNavigationGroup(): ?string
    {
        return 'Master Data';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Region')
                    ->columns(2)
                    ->components([
                        TextInput::make('nama_region')
                            ->label('Nama Region')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('nama_rbh')
                            ->label('Nama RBH'),
                    ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil Region')
                    ->columns(2)
                    ->components([
                        TextEntry::make('nama_region')
                            ->label('Nama Region'),
                        TextEntry::make('nama_rbh')
                            ->label('Nama RBH')
                            ->state(function (Region $record): ?string {
                                if (filled($record->nama_rbh)) {
                                    return $record->nama_rbh;
                                }

                                return Employee::query()
                                    ->where('region', $record->nama_region)
                                    ->whereHas('jobRole', fn ($q) => $q->where('code', 'RBH01'))
                                    ->value('nama_lengkap')
                                    ?? Employee::query()
                                        ->where('region', $record->nama_region)
                                        ->whereHas('jobRole', fn ($q) => $q->where('code', 'RBH01'))
                                        ->value('full_name');
                            })
                            ->placeholder('-'),
                    ]),
                Section::make('Ringkasan')
                    ->columns(3)
                    ->components([
                        TextEntry::make('areas_count')
                            ->label('Jumlah Area')
                            ->state(fn (Region $record): int => $record->areas()->count()),
                        TextEntry::make('branches_count')
                            ->label('Jumlah Cabang')
                            ->state(fn (Region $record): int => $record->branches()->count()),
                        TextEntry::make('employees_count')
                            ->label('Jumlah Karyawan')
                            ->state(fn (Region $record): int => $record->employees()->count()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('areas'))
            ->columns([
                TextColumn::make('nama_region')
                    ->label('Nama Region')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_rbh')
                    ->label('Nama RBH')
                    ->searchable()
                    ->getStateUsing(function (Region $record): ?string {
                        if (filled($record->nama_rbh)) {
                            return $record->nama_rbh;
                        }

                        return Employee::query()
                            ->where('region', $record->nama_region)
                            ->whereHas('jobRole', fn ($q) => $q->where('code', 'RBH01'))
                            ->value('nama_lengkap')
                            ?? Employee::query()
                                ->where('region', $record->nama_region)
                                ->whereHas('jobRole', fn ($q) => $q->where('code', 'RBH01'))
                                ->value('full_name');
                    })
                    ->toggleable(),
                TextColumn::make('areas_count')
                    ->label('Jml. Area')
                    ->sortable(),
            ])
            ->recordUrl(fn (Region $record): string => static::getUrl('view', ['record' => $record]))
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
            AreasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegions::route('/'),
            'create' => CreateRegion::route('/create'),
            'view' => ViewRegion::route('/{record}'),
            'edit' => EditRegion::route('/{record}/edit'),
        ];
    }
}
