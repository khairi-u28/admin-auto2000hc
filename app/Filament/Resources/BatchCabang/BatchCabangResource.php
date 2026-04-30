<?php
namespace App\Filament\Resources\BatchCabang;

use App\Filament\Resources\BatchCabang\Pages\CreateBatchCabang;
use App\Filament\Resources\BatchCabang\Pages\EditBatchCabang;
use App\Filament\Resources\BatchCabang\Pages\ListBatchCabang;
use App\Filament\Resources\Batches\Shared\BatchForm;
use App\Filament\Resources\Batches\Shared\BatchTable;
use App\Models\Batch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BatchCabangResource extends Resource
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
        return 2;
    }

    public static function getModelLabel(): string
    {
        return 'Batch Training Cabang';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Training Cabang';
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('type', 'Cabang');
    }

    public static function form(Schema $schema): Schema
    {
        return BatchForm::configure($schema, 'Cabang');
    }

    public static function table(Table $table): Table
    {
        return BatchTable::configure($table, 'Cabang');
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