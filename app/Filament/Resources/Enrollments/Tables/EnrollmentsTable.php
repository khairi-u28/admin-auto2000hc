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
                TextColumn::make('batch.name')
                    ->label('Batch Training')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu_undangan' => 'secondary',
                        'sedang_berjalan' => 'primary',
                        'lulus'   => 'success',
                        'Terlambat'     => 'danger',
                        default       => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'menunggu_undangan' => 'Menunggu Undangan',
                        'sedang_berjalan' => 'Sedang Berjalan',
                        'lulus'   => 'Lulus',
                        'Terlambat'     => 'Terlambat',
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
                        'menunggu_undangan' => 'Menunggu Undangan',
                        'sedang_berjalan' => 'Sedang Berjalan',
                        'lulus'   => 'Lulus',
                        'Terlambat'     => 'Terlambat',
                    ]),
                SelectFilter::make('batch_id')
                    ->label('Batch Training')
                    ->relationship('batch', 'name')
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

