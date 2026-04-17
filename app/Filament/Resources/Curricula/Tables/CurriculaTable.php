<?php

namespace App\Filament\Resources\Curricula\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CurriculaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department')
                    ->label('Departemen')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sales'      => 'primary',
                        'Aftersales' => 'success',
                        'PD'         => 'warning',
                        'HC'         => 'danger',
                        'GS'         => 'info',
                        default      => 'secondary',
                    }),
                TextColumn::make('jobRole.name')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('academic_year')
                    ->label('Tahun Akademik')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft'     => 'warning',
                        'published' => 'success',
                        default     => 'secondary',
                    }),
            ])
            ->filters([
                SelectFilter::make('department')
                    ->label('Departemen')
                    ->options([
                        'Sales'      => 'Sales',
                        'Aftersales' => 'Aftersales',
                        'PD'         => 'PD',
                        'HC'         => 'HC',
                        'GS'         => 'GS',
                        'Other'      => 'Lainnya',
                    ]),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft'     => 'Draft',
                        'published' => 'Published',
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
