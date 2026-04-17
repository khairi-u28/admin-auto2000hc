<?php

namespace App\Filament\Resources\TrainingRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TrainingRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('completion_date', 'desc')
            ->columns([
                TextColumn::make('employee.nrp')
                    ->label('NRP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('competencyTrack.name')
                    ->label('Track Kompetensi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('level_achieved')
                    ->label('Level')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        0 => 'secondary',
                        1 => 'warning',
                        2 => 'info',
                        3 => 'success',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        0 => 'Belum Training',
                        1 => 'Level 1',
                        2 => 'Level 2',
                        3 => 'Certified',
                        default => (string) $state,
                    })
                    ->sortable(),
                TextColumn::make('completion_date')
                    ->label('Tgl Selesai')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('source')
                    ->label('Sumber')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'import' => 'warning',
                        'manual' => 'primary',
                        'system' => 'info',
                        default  => 'secondary',
                    }),
            ])
            ->filters([
                SelectFilter::make('level_achieved')
                    ->label('Level')
                    ->options([
                        0 => 'Belum Training',
                        1 => 'Level 1',
                        2 => 'Level 2',
                        3 => 'Certified',
                    ]),
                SelectFilter::make('source')
                    ->label('Sumber')
                    ->options([
                        'import' => 'Import',
                        'manual' => 'Manual',
                        'system' => 'Sistem',
                    ]),
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
