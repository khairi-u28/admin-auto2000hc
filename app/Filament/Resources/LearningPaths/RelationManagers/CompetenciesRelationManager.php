<?php

namespace App\Filament\Resources\LearningPaths\RelationManagers;

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

class CompetenciesRelationManager extends RelationManager
{
    protected static string $relationship = 'competencies';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'Kompetensi Terkait';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('code')
                    ->label('Kode'),
                TextColumn::make('name')
                    ->label('Nama Kompetensi'),
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

