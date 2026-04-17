<?php

namespace App\Filament\Resources\JobRoles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class JobRolesTable
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
                    ->label('Nama Jabatan')
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
                TextColumn::make('level')
                    ->label('Level')
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->sortable(),
                TextColumn::make('competency_requirements_count')
                    ->label('Jml. Kompetensi')
                    ->counts('competencyRequirements')
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
