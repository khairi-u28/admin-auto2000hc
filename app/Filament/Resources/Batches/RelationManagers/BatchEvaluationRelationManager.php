<?php

namespace App\Filament\Resources\Batches\RelationManagers;

use App\Models\Batch;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BatchEvaluationRelationManager extends RelationManager
{
    protected static string $relationship = 'evaluationRelation';

    protected static ?string $title = 'Evaluasi';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }



    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->whereKey($this->getOwnerRecord()->getKey()))
            ->content(fn ($records) => view('filament.pages.batch-evaluation-bento', ['records' => $records]))
            ->columns([])
            ->actions([])
            ->bulkActions([])
            ->headerActions([]);
    }
}

