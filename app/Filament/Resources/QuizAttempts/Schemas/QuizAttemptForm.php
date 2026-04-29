<?php

namespace App\Filament\Resources\QuizAttempts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuizAttemptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Percobaan Quiz')
                ->columns(2)
                ->components([
                    Select::make('batch_participant_id')
                        ->label('Peserta Batch')
                        ->relationship('batchParticipant', 'id')
                        ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->employee?->full_name} — {$record->curriculum?->title}"
                        )
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('course_id')
                        ->label('Materi')
                        ->relationship('course', 'title')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('attempt_number')
                        ->label('Nomor Percobaan')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->default(1),
                    TextInput::make('score')
                        ->label('Nilai')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100),
                    Toggle::make('passed')
                        ->label('Lulus'),
                    DateTimePicker::make('submitted_at')
                        ->label('Waktu Submit'),
                ]),
        ]);
    }
}

