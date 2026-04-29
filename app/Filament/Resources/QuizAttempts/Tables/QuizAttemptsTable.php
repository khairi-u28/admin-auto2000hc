<?php

namespace App\Filament\Resources\QuizAttempts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class QuizAttemptsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('batchParticipant.employee.nrp')
                    ->label('NRP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('batchParticipant.employee.full_name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('course.title')
                    ->label('Materi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('attempt_number')
                    ->label('Percobaan ke-')
                    ->sortable(),
                TextColumn::make('score')
                    ->label('Nilai')
                    ->sortable(),
                IconColumn::make('passed')
                    ->label('Lulus')
                    ->boolean(),
                TextColumn::make('submitted_at')
                    ->label('Submit')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('passed')
                    ->label('Status Lulus'),
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

