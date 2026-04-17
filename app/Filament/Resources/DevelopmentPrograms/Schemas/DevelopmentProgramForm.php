<?php

namespace App\Filament\Resources\DevelopmentPrograms\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DevelopmentProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Program Pengembangan')
                ->columns(2)
                ->components([
                    Select::make('employee_id')
                        ->label('Karyawan')
                        ->relationship('employee', 'full_name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('program_name')
                        ->label('Nama Program')
                        ->required()
                        ->maxLength(100)
                        ->helperText('Contoh: MDP SH, MDP BC, ASDP, MDP FAH'),
                    Select::make('status')
                        ->label('Status')
                        ->required()
                        ->options([
                            'on_going'      => 'On Going',
                            'promoted'      => 'Promoted',
                            'failed'        => 'Failed',
                            'pool_of_cadre' => 'Pool of Cadre',
                        ]),
                    TextInput::make('period_year')
                        ->label('Tahun Periode')
                        ->required()
                        ->numeric()
                        ->minValue(2000)
                        ->maxValue(2100),
                    TextInput::make('kpi_mid_year')
                        ->label('KPI Mid Year')
                        ->numeric()
                        ->step(0.01),
                    TextInput::make('kpi_full_year')
                        ->label('KPI Full Year')
                        ->numeric()
                        ->step(0.01),
                    TextInput::make('pk_score')
                        ->label('PK Score')
                        ->maxLength(5)
                        ->helperText('Contoh: A, B, C'),
                ]),
        ]);
    }
}
