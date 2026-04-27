<?php

namespace App\Filament\Resources\Branches\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BranchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_cabang')
                    ->label('Kode Cabang')
                    ->getStateUsing(fn ($record) => $record->kode_cabang)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama')
                    ->label('Nama Cabang')
                    ->getStateUsing(fn ($record) => $record->nama)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('region_label')
                    ->label('Region')
                    ->getStateUsing(fn ($record) => $record->regionRelation?->nama_region ?? $record->region)
                    ->sortable(),
                TextColumn::make('area_label')
                    ->label('Area')
                    ->getStateUsing(fn ($record) => $record->areaRelation?->nama_area ?? $record->area)
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'GR'    => 'primary',
                        'BP'    => 'warning',
                        'HO'    => 'success',
                        default => 'secondary',
                    }),
                TextColumn::make('employees_count')
                    ->label('Jml. Karyawan')
                    ->counts('employees')
                    ->sortable(),
            ])
            ->recordUrl(fn ($record): string => \App\Filament\Resources\Branches\BranchResource::getUrl('view', ['record' => $record]))
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'GR' => 'GR',
                        'BP' => 'BP',
                        'HO' => 'HO',
                    ]),
                SelectFilter::make('region')
                    ->label('Region')
                    ->options(fn () => \App\Models\Branch::distinct()->pluck('region', 'region')->filter()->toArray()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
