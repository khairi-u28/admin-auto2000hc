<?php

namespace App\Filament\Resources\JobRoles\RelationManagers;

use App\Filament\Pages\OneSheetProfilePage;
use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CompetencyRequirementsRelationManager extends RelationManager
{
    protected static ?string $title = 'Daftar Karyawan';

    protected static string $relationship = 'competencyRequirements';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    protected function getTableQuery(): Builder
    {
        $jobRoleId = $this->getOwnerRecord()?->id;

        return Employee::query()
            ->with(['branch.areaRelation.region', 'branch.regionRelation'])
            ->where('job_role_id', $jobRoleId);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nrp')
                    ->label('NRP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_lengkap')
                    ->label('Nama')
                    ->state(fn (Employee $record): string => $record->nama_lengkap ?? $record->full_name ?? '-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('branch.kode_cabang')
                    ->label('Kode Cabang')
                    ->state(fn (Employee $record): string => $record->branch?->kode_cabang ?? $record->branch?->code ?? '-')
                    ->sortable(),
                TextColumn::make('branch.nama')
                    ->label('Nama Cabang')
                    ->state(fn (Employee $record): string => $record->branch?->nama ?? $record->branch?->name ?? '-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('area')
                    ->label('Area')
                    ->state(fn (Employee $record): string => $record->branch?->areaRelation?->nama_area ?? $record->area ?? '-')
                    ->sortable(),
                TextColumn::make('region')
                    ->label('Region')
                    ->state(fn (Employee $record): string => $record->branch?->regionRelation?->nama_region ?? $record->region ?? '-')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('onesheet')
                    ->label('One Sheet')
                    ->icon('heroicon-o-identification')
                    ->url(fn (Employee $record): string => OneSheetProfilePage::getUrl(['employee' => $record->id])),
            ])
            ->defaultSort('nama_lengkap');
    }
}

