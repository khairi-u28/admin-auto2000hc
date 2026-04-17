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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

class TrainingRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'trainingRecords';

    protected static ?string $title = 'Rekam Pelatihan';

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
                Select::make('level_achieved')
                    ->label('Level Dicapai')
                    ->options([
                        0 => 'Level 0',
                        1 => 'Level 1',
                        2 => 'Level 2',
                        3 => 'Level 3',
                    ])
                    ->required(),
                DatePicker::make('completion_date')
                    ->label('Tanggal Selesai')
                    ->required(),
                Select::make('source')
                    ->label('Sumber')
                    ->options([
                        'import' => 'Import',
                        'manual' => 'Manual',
                        'system' => 'Sistem',
                    ])
                    ->default('manual')
                    ->required(),
                TextInput::make('certification_number')
                    ->label('No. Sertifikat')
                    ->maxLength(100),
                DatePicker::make('certification_expiry')
                    ->label('Kedaluwarsa Sertifikat'),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->columnSpanFull()
                    ->rows(2),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('completion_date', 'desc')
            ->columns([
                TextColumn::make('competencyTrack.name')
                    ->label('Kompetensi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('level_achieved')
                    ->label('Level')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        0 => 'secondary',
                        1 => 'warning',
                        2 => 'primary',
                        3 => 'success',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (int $state): string => "Level {$state}")
                    ->sortable(),
                TextColumn::make('completion_date')
                    ->label('Tgl Selesai')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('certification_number')
                    ->label('No. Sertifikat')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('certification_expiry')
                    ->label('Kedaluwarsa')
                    ->date('d/m/Y')
                    ->placeholder('-'),
                TextColumn::make('source')
                    ->label('Sumber')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'import' => 'warning',
                        'manual' => 'primary',
                        'system' => 'info',
                        default  => 'secondary',
                    }),
            ])
            ->filters([
                SelectFilter::make('source')
                    ->label('Sumber')
                    ->options([
                        'import' => 'Import',
                        'manual' => 'Manual',
                        'system' => 'Sistem',
                    ]),
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
