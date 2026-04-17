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
                TextColumn::make('full_name')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jobRole.name')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->label('Cabang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('hav_category')
                    ->label('Kategori HAV')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Talent'       => 'success',
                        'Potential'    => 'primary',
                        'Core'         => 'warning',
                        'Underperform' => 'danger',
                        default        => 'secondary',
                    })
                    ->placeholder('-'),
                TextColumn::make('hav_score')
                    ->label('HAV Score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('employee_type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'VSP'   => 'primary',
                        'BP'    => 'success',
                        'THS'   => 'warning',
                        'HO'    => 'info',
                        default => 'secondary',
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active'   => 'success',
                        'inactive' => 'danger',
                        default    => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active'   => 'Aktif',
                        'inactive' => 'Tidak Aktif',
                        default    => $state,
                    }),
                TextColumn::make('entry_date')
                    ->label('Tgl Masuk')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active'   => 'Aktif',
                        'inactive' => 'Tidak Aktif',
                    ]),
                SelectFilter::make('employee_type')
                    ->label('Tipe Karyawan')
                    ->options([
                        'VSP'   => 'VSP',
                        'BP'    => 'BP',
                        'THS'   => 'THS',
                        'HO'    => 'HO',
                        'Other' => 'Lainnya',
                    ]),
                SelectFilter::make('branch_id')
                    ->label('Cabang')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('job_role_id')
                    ->label('Jabatan')
                    ->relationship('jobRole', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('hav_category')
                    ->label('Kategori HAV')
                    ->options([
                        'Talent'       => 'Talent',
                        'Potential'    => 'Potential',
                        'Core'         => 'Core',
                        'Underperform' => 'Underperform',
                    ]),
            ])
            ->recordActions([
                Action::make('view_onesheet')
                    ->label('One Sheet')
                    ->icon(Heroicon::OutlinedIdentification)
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
