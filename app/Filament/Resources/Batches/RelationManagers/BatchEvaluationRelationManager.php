<?php

namespace App\Filament\Resources\Batches\RelationManagers;

use App\Models\Batch;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BatchEvaluationRelationManager extends RelationManager
{
    protected static string $relationship = 'evaluationRelation'; // We'll add this dummy relation

    protected static ?string $title = 'Evaluasi';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
                RichEditor::make('evaluation')
                    ->label('Evaluasi Akhir')
                    ->placeholder('Isi evaluasi training di sini...')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('evaluation')
                    ->label('Isi Evaluasi')
                    ->html()
                    ->limit(100),
            ])
            ->actions([
                EditAction::make()
                    ->label('Edit Evaluasi')
                    ->modalHeading('Edit Evaluasi Batch')
                    ->visible(fn ($record) => $record->status === 'selesai'),
            ]);
    }
}
