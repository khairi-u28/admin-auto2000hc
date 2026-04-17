<?php

namespace App\Filament\Resources\TrainingRecords\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TrainingRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Pelatihan')
                ->columns(2)
                ->components([
                    Select::make('employee_id')
                        ->label('Karyawan')
                        ->relationship('employee', 'full_name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('competency_track_id')
                        ->label('Track Kompetensi')
                        ->relationship('competencyTrack', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('level_achieved')
                        ->label('Level Dicapai')
                        ->required()
                        ->options([
                            0 => 'Belum Training',
                            1 => 'Level 1',
                            2 => 'Level 2',
                            3 => 'Certified / Lulus',
                        ])
                        ->default(0),
                    Select::make('source')
                        ->label('Sumber Data')
                        ->required()
                        ->options([
                            'import' => 'Import',
                            'manual' => 'Manual',
                            'system' => 'Sistem',
                        ])
                        ->default('manual'),
                    DatePicker::make('completion_date')
                        ->label('Tanggal Selesai'),
                    TextInput::make('certification_number')
                        ->label('No. Sertifikat')
                        ->maxLength(100),
                    DatePicker::make('certification_expiry')
                        ->label('Kadaluarsa Sertifikat'),
                    Textarea::make('notes')
                        ->label('Catatan')
                        ->columnSpanFull()
                        ->rows(3),
                ]),
        ]);
    }
}
