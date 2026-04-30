<?php
namespace App\Filament\Resources\BatchHO;

use App\Filament\Resources\BatchHO\Pages\CreateBatchHO;
use App\Filament\Resources\BatchHO\Pages\EditBatchHO;
use App\Filament\Resources\BatchHO\Pages\ListBatchHO;
use App\Filament\Resources\Batches\Shared\BatchForm;
use App\Filament\Resources\Batches\Shared\BatchTable;
use App\Models\Batch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BatchHOResource extends Resource
{
    protected static ?string $model = Batch::class;

    public static function getNavigationGroup(): ?string
    {
        return 'Training Management';
    }

    public static function getNavigationIcon(): Heroicon|string|null
    {
        return Heroicon::OutlinedBuildingOffice;
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getModelLabel(): string
    {
        return 'Batch Training HO';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Training HO';
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('type', 'HO');
    }

    public static function form(Schema $schema): Schema
    {
        return BatchForm::configure($schema, 'HO');
    }

    public static function table(Table $table): Table
    {
        return BatchTable::configure($table, 'HO');
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