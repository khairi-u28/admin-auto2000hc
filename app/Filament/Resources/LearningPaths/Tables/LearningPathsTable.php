<?php

namespace App\Filament\Resources\LearningPaths\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\EditAction;

class LearningPathsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jobRole.code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jobRole.name')
                    ->label('Nama Jabatan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('jobRole.department')
                    ->label('Departemen')
                    ->sortable(),
                TextColumn::make('jobRole.level')
                    ->label('Level')
                    ->sortable(),
                TextColumn::make('competencies_count')
                    ->label('Jml Kompetensi')
                    ->counts('competencies')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('job_role_id')
                    ->label('Jabatan')
                    ->relationship('jobRole', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
            ])
            ->actions([
                EditAction::make(),
            ]);
    }
}



