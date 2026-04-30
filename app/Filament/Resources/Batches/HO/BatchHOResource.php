<?php

namespace App\Filament\Resources\Batches\HO;

use App\Filament\Resources\Batches\HO\Pages\CreateBatchHO;
use App\Filament\Resources\Batches\HO\Pages\EditBatchHO;
use App\Filament\Resources\Batches\HO\Pages\ListBatchHO;
use App\Filament\Resources\Batches\RelationManagers\BatchEvaluationRelationManager;
use App\Filament\Resources\Batches\RelationManagers\BatchFeedbackRelationManager;
use App\Filament\Resources\Batches\RelationManagers\BatchMateriRelationManager;
use App\Filament\Resources\Batches\RelationManagers\BatchParticipantsRelationManager;
use App\Filament\Resources\Batches\Shared\BatchSchemas;
use App\Models\Batch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BatchHOResource extends Resource
{
    protected static ?string $model = Batch::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Training HO';

    protected static ?string $pluralModelLabel = 'Training HO';

    protected static ?string $slug = 'training-ho';

    public static function getNavigationGroup(): ?string
    {
        return 'Training Management';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', 'ho');
    }

    public static function form(Schema $schema): Schema
    {
        return BatchSchemas::form($schema, 'ho');
    }

    public static function table(Table $table): Table
    {
        return BatchSchemas::table($table, 'ho');
    }

    public static function getRelations(): array
    {
        return [
            BatchParticipantsRelationManager::class,
            BatchMateriRelationManager::class,
            BatchFeedbackRelationManager::class,
            BatchEvaluationRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBatchHO::route('/'),
            'create' => CreateBatchHO::route('/create'),
            'edit'   => EditBatchHO::route('/{record}/edit'),
        ];
    }
}
