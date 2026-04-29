<?php

namespace App\Filament\Resources\Enrollments;

use App\Filament\Resources\Enrollments\Pages\CreateEnrollment;
use App\Filament\Resources\Enrollments\Pages\EditEnrollment;
use App\Filament\Resources\Enrollments\Pages\ListEnrollments;
use App\Filament\Resources\Enrollments\Schemas\EnrollmentForm;
use App\Filament\Resources\Enrollments\Tables\EnrollmentsTable;
use App\Filament\Resources\Enrollments\RelationManagers\QuizAttemptsRelationManager;
use App\Models\BatchParticipant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EnrollmentResource extends Resource
{
    protected static ?string $model = BatchParticipant::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 0;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $modelLabel = 'Peserta Batch';

    protected static ?string $pluralModelLabel = 'Peserta Batch';

    public static function getNavigationGroup(): ?string
    {
        return 'Operasional & Training';
    }

    public static function form(Schema $schema): Schema
    {
        return EnrollmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EnrollmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            QuizAttemptsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListEnrollments::route('/'),
            'create' => CreateEnrollment::route('/create'),
            'edit'   => EditEnrollment::route('/{record}/edit'),
        ];
    }
}



