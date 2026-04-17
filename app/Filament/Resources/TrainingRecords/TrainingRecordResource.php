<?php

namespace App\Filament\Resources\TrainingRecords;

use App\Filament\Imports\TrainingRecordImporter;
use App\Filament\Resources\TrainingRecords\Pages\CreateTrainingRecord;
use App\Filament\Resources\TrainingRecords\Pages\EditTrainingRecord;
use App\Filament\Resources\TrainingRecords\Pages\ListTrainingRecords;
use App\Filament\Resources\TrainingRecords\Schemas\TrainingRecordForm;
use App\Filament\Resources\TrainingRecords\Tables\TrainingRecordsTable;
use App\Models\TrainingRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TrainingRecordResource extends Resource
{
    protected static ?string $model = TrainingRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?int $navigationSort = 5;

    protected static ?string $modelLabel = 'Rekam Pelatihan';

    protected static ?string $pluralModelLabel = 'Rekam Pelatihan';

    public static function getNavigationGroup(): ?string
    {
        return 'Enrollment & Operasional';
    }

    public static function form(Schema $schema): Schema
    {
        return TrainingRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrainingRecordsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListTrainingRecords::route('/'),
            'create' => CreateTrainingRecord::route('/create'),
            'edit'   => EditTrainingRecord::route('/{record}/edit'),
        ];
    }
}
