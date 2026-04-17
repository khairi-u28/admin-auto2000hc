<?php

namespace App\Filament\Resources\CompetencyTracks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CompetencyTracksTable
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
                    ->label('Nama Track')
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
                TextColumn::make('category')
                    ->label('Kategori')
                    ->sortable(),
                TextColumn::make('level_sequence')
                    ->label('Level')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => 'Level 1 (Entry)',
                        2 => 'Level 2 (Intermediate)',
                        3 => 'Level 3 (Expert)',
                        default => (string) $state,
                    })
                    ->sortable(),
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
                SelectFilter::make('level_sequence')
                    ->label('Level')
                    ->options([
                        1 => 'Level 1 (Entry)',
                        2 => 'Level 2 (Intermediate)',
                        3 => 'Level 3 (Expert)',
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
