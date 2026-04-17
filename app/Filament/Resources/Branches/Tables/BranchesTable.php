<?php

namespace App\Filament\Resources\Branches\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BranchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Cabang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('region')
                    ->label('Region')
                    ->sortable(),
                TextColumn::make('area')
                    ->label('Area')
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
