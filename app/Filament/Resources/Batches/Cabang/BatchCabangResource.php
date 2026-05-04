<?php

namespace App\Filament\Resources\Batches\Cabang;

use App\Filament\Resources\Batches\Cabang\Pages\CreateBatchCabang;
use App\Filament\Resources\Batches\Cabang\Pages\EditBatchCabang;
use App\Filament\Resources\Batches\Cabang\Pages\ListBatchCabang;
use App\Filament\Resources\Batches\RelationManagers\BatchFeedbackRelationManager;
use App\Filament\Resources\Batches\RelationManagers\BatchMateriRelationManager;
use App\Filament\Resources\Batches\RelationManagers\BatchParticipantsRelationManager;
use App\Filament\Resources\Batches\RelationManagers\BatchTrainersRelationManager;
use App\Filament\Resources\Batches\Shared\BatchSchemas;
use App\Models\Batch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

use App\Filament\Resources\Batches\RelationManagers\BatchEvaluationRelationManager;

class BatchCabangResource extends Resource
{
    protected static ?string $model = Batch::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Mandatory & Thematic';

    protected static ?string $pluralModelLabel = 'Mandatory & Thematic';

    protected static ?string $slug = 'training-cabang';

    public static function getNavigationGroup(): ?string
    {
        return 'Training Management';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', 'cabang');
    }

    public static function form(Schema $schema): Schema
    {
        return BatchSchemas::form($schema, 'cabang');
    }

    public static function table(Table $table): Table
    {
        return BatchSchemas::table($table, 'cabang');
    }

    public static function getRelations(): array
    {
        return [
            BatchParticipantsRelationManager::class,
            BatchTrainersRelationManager::class,
            BatchMateriRelationManager::class,
            BatchFeedbackRelationManager::class,
            BatchEvaluationRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBatchCabang::route('/'),
            'create' => CreateBatchCabang::route('/create'),
            'edit'   => EditBatchCabang::route('/{record}/edit'),
        ];
    }
}
