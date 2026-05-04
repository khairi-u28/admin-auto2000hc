<?php

namespace App\Filament\Resources\Branches\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    protected static ?string $title = 'Daftar Karyawan';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nrp')
                    ->label('NRP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('full_name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('position_name')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('one_sheet_link')
                    ->label('')
                    ->state('Profile')
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->url(fn($record): string => \App\Filament\Pages\OneSheetProfilePage::getUrl(['employee' => $record->id]))
                    ->extraAttributes(['style' => 'font-weight: 600;']),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make()
                    ->url(fn($record): string => \App\Filament\Resources\Employees\EmployeeResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
