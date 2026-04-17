<?php

namespace App\Filament\Resources\Employees\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class DevelopmentProgramsRelationManager extends RelationManager
{
    protected static string $relationship = 'developmentPrograms';

    protected static ?string $title = 'Program Pengembangan';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->components([
                TextInput::make('program_name')
                    ->label('Nama Program')
                    ->required()
                    ->maxLength(100)
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'on_going'      => 'Sedang Berjalan',
                        'promoted'      => 'Dipromosikan',
                        'failed'        => 'Tidak Lulus',
                        'pool_of_cadre' => 'Pool of Cadre',
                    ])
                    ->required(),
                TextInput::make('period_year')
                    ->label('Tahun Periode')
                    ->numeric()
                    ->minValue(2000)
                    ->maxValue(2100),
                TextInput::make('kpi_mid_year')
                    ->label('KPI Mid Year')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
                TextInput::make('kpi_full_year')
                    ->label('KPI Full Year')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
                TextInput::make('pk_score')
                    ->label('PK Score')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('period_year', 'desc')
            ->columns([
                TextColumn::make('program_name')
                    ->label('Nama Program')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('period_year')
                    ->label('Tahun')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'on_going'      => 'primary',
                        'promoted'      => 'success',
                        'failed'        => 'danger',
                        'pool_of_cadre' => 'warning',
                        default         => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'on_going'      => 'Sedang Berjalan',
                        'promoted'      => 'Dipromosikan',
                        'failed'        => 'Tidak Lulus',
                        'pool_of_cadre' => 'Pool of Cadre',
                        default         => $state,
                    }),
                TextColumn::make('kpi_mid_year')
                    ->label('KPI Mid')
                    ->sortable(),
                TextColumn::make('kpi_full_year')
                    ->label('KPI Full')
                    ->sortable(),
                TextColumn::make('pk_score')
                    ->label('PK Score')
                    ->sortable(),
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
