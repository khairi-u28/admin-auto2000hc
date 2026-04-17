<?php

namespace App\Filament\Resources\DevelopmentPrograms\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DevelopmentProgramsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('period_year', 'desc')
            ->columns([
                TextColumn::make('employee.nrp')
                    ->label('NRP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('program_name')
                    ->label('Program')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'on_going'      => 'primary',
                        'promoted'      => 'success',
                        'failed'        => 'danger',
                        'pool_of_cadre' => 'warning',
                        default         => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'on_going'      => 'On Going',
                        'promoted'      => 'Promoted',
                        'failed'        => 'Failed',
                        'pool_of_cadre' => 'Pool of Cadre',
                        default         => $state,
                    }),
                TextColumn::make('period_year')
                    ->label('Periode')
                    ->sortable(),
                TextColumn::make('kpi_full_year')
                    ->label('KPI Full Year')
                    ->sortable(),
                TextColumn::make('pk_score')
                    ->label('PK Score'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'on_going'      => 'On Going',
                        'promoted'      => 'Promoted',
                        'failed'        => 'Failed',
                        'pool_of_cadre' => 'Pool of Cadre',
                    ]),
                SelectFilter::make('period_year')
                    ->label('Tahun Periode')
                    ->options(
                        array_combine(range(2020, (int) date('Y') + 1), range(2020, (int) date('Y') + 1))
                    ),
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
