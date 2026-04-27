<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nrp')
                    ->label('NRP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->getStateUsing(fn ($record) => $record->nama_lengkap)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position_name')
                    ->label('Position Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pos')
                    ->label('POS')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('branch.kode_cabang')
                    ->label('Kode Cabang')
                    ->getStateUsing(fn ($record) => $record->branch?->kode_cabang)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('branch.nama')
                    ->label('Cabang')
                    ->getStateUsing(fn ($record) => $record->branch?->nama ?? $record->branch?->name)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('area_label')
                    ->label('Area')
                    ->getStateUsing(fn ($record) => $record->branch?->areaRelation?->nama_area ?? $record->area)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('region_label')
                    ->label('Region')
                    ->getStateUsing(fn ($record) => $record->branch?->regionRelation?->nama_region ?? $record->region)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('masa_bakti')
                    ->label('Masa Bakti')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active'   => 'success',
                        'inactive' => 'warning',
                        default    => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active'   => 'aktif',
                        'inactive' => 'non_aktif',
                        default    => $state,
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active'   => 'aktif',
                        'inactive' => 'non_aktif',
                    ]),
                SelectFilter::make('branch_id')
                    ->label('Cabang')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('region')
                    ->label('Region')
                    ->options(fn () => \App\Models\Employee::query()->whereNotNull('region')->orderBy('region')->distinct()->pluck('region', 'region')->toArray()),
                SelectFilter::make('area')
                    ->label('Area')
                    ->options(fn () => \App\Models\Employee::query()->whereNotNull('area')->orderBy('area')->distinct()->pluck('area', 'area')->toArray()),
                SelectFilter::make('position_name')
                    ->label('Position Name')
                    ->options(fn () => \App\Models\Employee::query()->whereNotNull('position_name')->orderBy('position_name')->distinct()->pluck('position_name', 'position_name')->toArray())
                    ->searchable()
                    ->preload(),
            ])
            ->recordUrl(fn ($record): string => \App\Filament\Pages\OneSheetProfilePage::getUrl(['employee' => $record->id]))
            ->recordActions([
                Action::make('view_onesheet')
                    ->label('One Sheet')
                    ->icon('heroicon-o-identification')
                    ->color('info')
                    ->url(fn ($record) => \App\Filament\Pages\OneSheetProfilePage::getUrl(['employee' => $record->id])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
