<?php

namespace App\Filament\Resources\AreaResource\RelationManagers;

use App\Models\Branch;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BranchesRelationManager extends RelationManager
{
    protected static string $relationship = 'branches';

    protected static ?string $title = 'Daftar Cabang';

    protected function getTableQuery(): Builder
    {
        $area = $this->getOwnerRecord();

        return Branch::query()
            ->where('area_id', $area->getKey())
            ->orWhere('area', $area->nama_area);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('employees'))
            ->columns([
                TextColumn::make('kode_cabang')
                    ->label('Kode Cabang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama')
                    ->label('Nama Cabang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge(),
                TextColumn::make('employees_count')
                    ->label('Jml. Karyawan')
                    ->sortable(),
                TextColumn::make('area_penyelenggara')
                    ->label('Area Penyelenggara')
                    ->placeholder('-')
                    ->toggleable(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()
                    ->url(fn ($record): string => \App\Filament\Resources\Branches\BranchResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
