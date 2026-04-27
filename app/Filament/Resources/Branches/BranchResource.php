<?php

namespace App\Filament\Resources\Branches;

use App\Filament\Resources\Branches\Pages\CreateBranch;
use App\Filament\Resources\Branches\Pages\EditBranch;
use App\Filament\Resources\Branches\Pages\ListBranches;
use App\Filament\Resources\Branches\Pages\ViewBranch;
use App\Filament\Resources\Branches\RelationManagers\EmployeesRelationManager;
use App\Filament\Resources\Branches\Schemas\BranchForm;
use App\Filament\Resources\Branches\Tables\BranchesTable;
use App\Models\Branch;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Cabang';

    protected static ?string $pluralModelLabel = 'Cabang';

    public static function getNavigationGroup(): ?string
    {
        return 'Master Data';
    }

    public static function form(Schema $schema): Schema
    {
        return BranchForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil Cabang')
                    ->columns(2)
                    ->components([
                        TextEntry::make('kode_cabang')
                            ->label('Kode Cabang')
                            ->state(fn (Branch $record): ?string => $record->kode_cabang),
                        TextEntry::make('nama')
                            ->label('Nama Cabang')
                            ->state(fn (Branch $record): ?string => $record->nama),
                        TextEntry::make('region_label')
                            ->label('Region')
                            ->state(fn (Branch $record): ?string => $record->regionRelation?->nama_region ?? $record->region),
                        TextEntry::make('area_label')
                            ->label('Area')
                            ->state(fn (Branch $record): ?string => $record->areaRelation?->nama_area ?? $record->area),
                        TextEntry::make('type')
                            ->label('Tipe')
                            ->badge(),
                    ]),
                Section::make('Ringkasan LMS')
                    ->columns(3)
                    ->components([
                        TextEntry::make('total_karyawan')
                            ->label('Total Karyawan')
                            ->state(fn (Branch $record): int => $record->employees()->count()),
                        TextEntry::make('enrollment_selesai')
                            ->label('Enrollment Selesai')
                            ->state(fn (Branch $record): int => $record->employees()->whereHas('enrollments', fn ($query) => $query->where('status', 'completed'))->count()),
                        TextEntry::make('kepala_cabang')
                            ->label('Kepala Cabang')
                            ->state(fn (Branch $record): string => $record->employees()
                                ->where('position_name', 'like', '%Kepala Cabang%')
                                ->value('full_name') ?? '-'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return BranchesTable::configure($table);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['code', 'name', 'region', 'area'];
    }

    public static function getRelations(): array
    {
        return [
            EmployeesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBranches::route('/'),
            'create' => CreateBranch::route('/create'),
            'view' => ViewBranch::route('/{record}'),
            'edit'  => EditBranch::route('/{record}/edit'),
        ];
    }
}
