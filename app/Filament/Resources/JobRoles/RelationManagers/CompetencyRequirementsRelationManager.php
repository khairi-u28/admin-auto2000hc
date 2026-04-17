<?php

namespace App\Filament\Resources\JobRoles\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class CompetencyRequirementsRelationManager extends RelationManager
{
    protected static string $relationship = 'competencyRequirements';

    protected static ?string $title = 'Persyaratan Kompetensi';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->components([
                Select::make('competency_track_id')
                    ->label('Kompetensi')
                    ->relationship('competencyTrack', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('minimum_level')
                    ->label('Level Minimum')
                    ->options([
                        1 => 'Level 1',
                        2 => 'Level 2',
                        3 => 'Level 3',
                    ])
                    ->required(),
                Toggle::make('is_mandatory')
                    ->label('Wajib')
                    ->default(true),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('competencyTrack.name')
                    ->label('Kompetensi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('competencyTrack.department')
                    ->label('Departemen')
                    ->sortable(),
                TextColumn::make('minimum_level')
                    ->label('Level Min.')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'warning',
                        2 => 'primary',
                        3 => 'success',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (int $state): string => "Level {$state}")
                    ->sortable(),
                IconColumn::make('is_mandatory')
                    ->label('Wajib')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                CreateAction::make(),
            ]);
    }
}
