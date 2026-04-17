<?php

namespace App\Filament\Resources\Modules\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';

    protected static ?string $title = 'Materi';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Materi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department')
                    ->label('Departemen')
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'video'           => 'primary',
                        'pdf'             => 'warning',
                        'quiz'            => 'success',
                        'article'         => 'info',
                        'offline_session' => 'danger',
                        'online_session'  => 'secondary',
                        default           => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'video'           => 'Video',
                        'pdf'             => 'PDF',
                        'quiz'            => 'Quiz',
                        'article'         => 'Artikel',
                        'offline_session' => 'Sesi Offline',
                        'online_session'  => 'Sesi Online',
                        default           => $state,
                    }),
                TextColumn::make('duration_minutes')
                    ->label('Durasi (menit)')
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
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'video'           => 'Video',
                        'pdf'             => 'PDF',
                        'quiz'            => 'Quiz',
                        'article'         => 'Artikel',
                        'offline_session' => 'Sesi Offline',
                        'online_session'  => 'Sesi Online',
                    ]),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
                AttachAction::make()
                    ->recordTitleAttribute('title')
                    ->preloadRecordSelect(),
            ]);
    }
}
