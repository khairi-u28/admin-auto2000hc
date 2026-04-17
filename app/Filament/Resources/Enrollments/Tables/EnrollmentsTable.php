<?php

namespace App\Filament\Resources\Enrollments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EnrollmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.nrp')
                    ->label('NRP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee.full_name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('curriculum.title')
                    ->label('Kurikulum')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'not_started' => 'secondary',
                        'in_progress' => 'primary',
                        'completed'   => 'success',
                        'overdue'     => 'danger',
                        default       => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'not_started' => 'Belum Dimulai',
                        'in_progress' => 'Sedang Berjalan',
                        'completed'   => 'Selesai',
                        'overdue'     => 'Terlambat',
                        default       => $state,
                    }),
                TextColumn::make('progress_pct')
                    ->label('Progres')
                    ->formatStateUsing(fn (int $state): string => "{$state}%")
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label('Batas Waktu')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'not_started' => 'Belum Dimulai',
                        'in_progress' => 'Sedang Berjalan',
                        'completed'   => 'Selesai',
                        'overdue'     => 'Terlambat',
                    ]),
                SelectFilter::make('curriculum_id')
                    ->label('Kurikulum')
                    ->relationship('curriculum', 'title')
                    ->searchable()
                    ->preload(),
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
