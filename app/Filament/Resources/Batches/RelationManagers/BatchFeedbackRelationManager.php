<?php

namespace App\Filament\Resources\Batches\RelationManagers;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BatchFeedbackRelationManager extends RelationManager
{
    protected static string $relationship = 'feedback';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'Evaluasi & Feedback';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
                TextInput::make('employee_id')
                    ->label('Karyawan')
                    ->disabled()
                    ->formatStateUsing(fn ($record) => $record?->employee?->full_name),
                Toggle::make('is_submitted')
                    ->label('Sudah Submit')
                    ->disabled(),
                
                // Dimensi Training
                TextInput::make('training_relevance')->numeric()->disabled(),
                TextInput::make('training_material_quality')->numeric()->disabled(),
                TextInput::make('training_schedule')->numeric()->disabled(),
                TextInput::make('training_facility')->numeric()->disabled(),
                Textarea::make('training_comments')->columnSpanFull()->disabled(),
                
                // Dimensi Trainer
                TextInput::make('trainer_mastery')->numeric()->disabled(),
                TextInput::make('trainer_delivery')->numeric()->disabled(),
                TextInput::make('trainer_responsiveness')->numeric()->disabled(),
                TextInput::make('trainer_attitude')->numeric()->disabled(),
                Textarea::make('trainer_comments')->columnSpanFull()->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('is_submitted')
                    ->label('Status')
                    ->state(fn ($record) => $record->is_submitted ? 'Submitted' : 'Pending')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Submitted' => 'success',
                        'Pending'   => 'warning',
                        default     => 'secondary',
                    }),
                TextColumn::make('training_avg')
                    ->label('Rating Training')
                    ->numeric(1)
                    ->sortable(),
                TextColumn::make('trainer_avg')
                    ->label('Rating Trainer')
                    ->numeric(1)
                    ->sortable(),
                TextColumn::make('training_comments')
                    ->label('Catatan')
                    ->limit(30)
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Usually created by the system/users
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}


