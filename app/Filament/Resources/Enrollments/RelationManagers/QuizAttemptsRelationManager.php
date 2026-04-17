<?php

namespace App\Filament\Resources\Enrollments\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuizAttemptsRelationManager extends RelationManager
{
    protected static string $relationship = 'quizAttempts';

    protected static ?string $title = 'Percobaan Quiz';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('submitted_at', 'desc')
            ->columns([
                TextColumn::make('course.title')
                    ->label('Materi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('attempt_number')
                    ->label('Percobaan Ke-')
                    ->sortable(),
                TextColumn::make('score')
                    ->label('Skor')
                    ->sortable(),
                IconColumn::make('passed')
                    ->label('Lulus')
                    ->boolean(),
                TextColumn::make('submitted_at')
                    ->label('Waktu Submit')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
