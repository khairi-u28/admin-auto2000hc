<?php

namespace App\Filament\Resources\Courses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CoursesTable
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
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'video'           => 'primary',
                        'pdf'             => 'warning',
                        'article'         => 'info',
                        'quiz'            => 'success',
                        'offline_session' => 'danger',
                        'online_session'  => 'secondary',
                        default           => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'video'           => 'Video',
                        'pdf'             => 'PDF',
                        'article'         => 'Artikel',
                        'quiz'            => 'Quiz',
                        'offline_session' => 'Sesi Offline',
                        'online_session'  => 'Sesi Online',
                        default           => $state,
                    }),
                TextColumn::make('duration_minutes')
                    ->label('Durasi (mnt)')
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
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'video'           => 'Video',
                        'pdf'             => 'PDF',
                        'article'         => 'Artikel',
                        'quiz'            => 'Quiz',
                        'offline_session' => 'Sesi Offline',
                        'online_session'  => 'Sesi Online',
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
