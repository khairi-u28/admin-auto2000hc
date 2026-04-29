<?php

namespace App\Filament\Resources\Competencies\Tables;

use App\Models\Competency;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class CompetenciesTable
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
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tags')
                    ->label('Tags')
                    ->badge()
                    ->separator(',')
                    ->searchable(),
                TextColumn::make('modules_count')
                    ->label('Total Modul')
                    ->counts('modules')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('tags')
                    ->label('Tag')
                    ->multiple()
                    ->options([
                        'Sales' => 'Sales',
                        'Aftersales' => 'Aftersales',
                        'HO' => 'HO',
                        'People Development' => 'People Development',
                        'General' => 'General',
                    ])
                    ->query(function ($query, array $data) {
                        if (empty($data['values'])) {
                            return $query;
                        }
                        
                        // Because tags are stored as JSON array, we can filter using whereJsonContains
                        foreach ($data['values'] as $tag) {
                            $query->whereJsonContains('tags', $tag);
                        }
                        return $query;
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}



