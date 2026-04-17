<?php

namespace App\Filament\Resources\Curricula\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CurriculumForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Kurikulum')
                ->columns(2)
                ->components([
                    TextInput::make('title')
                        ->label('Judul Kurikulum')
                        ->required()
                        ->columnSpanFull()
                        ->maxLength(200),
                    Select::make('department')
                        ->label('Departemen')
                        ->required()
                        ->options([
                            'Sales'      => 'Sales',
                            'Aftersales' => 'Aftersales',
                            'PD'         => 'PD',
                            'HC'         => 'HC',
                            'GS'         => 'GS',
                            'Other'      => 'Lainnya',
                        ]),
                    Select::make('job_role_id')
                        ->label('Jabatan')
                        ->relationship('jobRole', 'name')
                        ->searchable()
                        ->preload(),
                    TextInput::make('academic_year')
                        ->label('Tahun Akademik')
                        ->required()
                        ->numeric()
                        ->minValue(2000)
                        ->maxValue(2100),
                    Select::make('status')
                        ->label('Status')
                        ->required()
                        ->options([
                            'draft'     => 'Draft',
                            'published' => 'Published',
                        ])
                        ->default('draft'),
                ]),
        ]);
    }
}
