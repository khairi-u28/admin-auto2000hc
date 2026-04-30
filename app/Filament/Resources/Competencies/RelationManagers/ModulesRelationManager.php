<?php

namespace App\Filament\Resources\Competencies\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'modules';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $title = 'Modul Terkait';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Modul'),
                TextColumn::make('order_index')
                    ->label('Urutan')
                    ->sortable(),
                IconColumn::make('is_mandatory')
                    ->label('Wajib')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('order_index')
                            ->label('Urutan')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        Toggle::make('is_mandatory')
                            ->label('Wajib')
                            ->default(true),
                    ]),
            ])
            ->actions([
                DetachAction::make(),
            ])
            ->bulkActions([
                DetachBulkAction::make(),
            ]);
    }
}

