<?php

namespace App\Filament\Resources\Enrollments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EnrollmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Enrollment')
                ->columns(2)
                ->components([
                    Select::make('employee_id')
                        ->label('Karyawan')
                        ->relationship('employee', 'full_name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('curriculum_id')
                        ->label('Kurikulum')
                        ->relationship('curriculum', 'title')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('status')
                        ->label('Status')
                        ->required()
                        ->options([
                            'not_started' => 'Belum Dimulai',
                            'in_progress' => 'Sedang Berjalan',
                            'completed'   => 'Selesai',
                            'overdue'     => 'Terlambat',
                        ])
                        ->default('not_started'),
                    TextInput::make('progress_pct')
                        ->label('Progres (%)')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->default(0),
                    DatePicker::make('due_date')
                        ->label('Batas Waktu'),
                    DateTimePicker::make('started_at')
                        ->label('Mulai Pada'),
                    DateTimePicker::make('completed_at')
                        ->label('Selesai Pada'),
                ]),
        ]);
    }
}
