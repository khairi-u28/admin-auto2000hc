<?php

namespace App\Filament\Resources\Batches\RelationManagers;

use App\Models\Employee;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BatchTrainersRelationManager extends RelationManager
{
    protected static string $relationship = 'trainers';

    protected static ?string $title = 'Trainer';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    protected function getTableQuery(): Builder
    {
        $batch = $this->getOwnerRecord();
        $picUserId = $batch?->pic_id;

        return Employee::query()
            ->with('branch.areaRelation.region')
            ->where('user_id', $picUserId);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Nama Trainer')
                    ->state(fn (Employee $record): string => $record->nama_lengkap ?? $record->full_name ?? '-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nrp')
                    ->label('NRP')
                    ->searchable(),
                TextColumn::make('branch.kode_cabang')
                    ->label('Kode Cabang')
                    ->state(fn (Employee $record): string => $record->branch?->kode_cabang ?? $record->branch?->code ?? '-'),
                TextColumn::make('branch.nama')
                    ->label('Nama Cabang')
                    ->state(fn (Employee $record): string => $record->branch?->nama ?? $record->branch?->name ?? '-'),
                TextColumn::make('area')
                    ->label('Area')
                    ->state(fn (Employee $record): string => $record->branch?->areaRelation?->nama_area ?? $record->area ?? '-'),
                TextColumn::make('region')
                    ->label('Region')
                    ->state(fn (Employee $record): string => $record->branch?->regionRelation?->nama_region ?? $record->region ?? '-'),
            ]);
    }
}

