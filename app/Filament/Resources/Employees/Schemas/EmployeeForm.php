<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Utama')
                ->columns(2)
                ->components([
                    TextInput::make('nrp')
                        ->label('NRP')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(20),
                    TextInput::make('full_name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(150),
                    TextInput::make('position_name')
                        ->label('Nama Posisi')
                        ->required()
                        ->maxLength(100),
                    Select::make('employee_type')
                        ->label('Tipe Karyawan')
                        ->required()
                        ->options([
                            'VSP'   => 'VSP',
                            'BP'    => 'BP',
                            'THS'   => 'THS',
                            'HO'    => 'HO',
                            'Other' => 'Lainnya',
                        ]),
                    Select::make('status')
                        ->label('Status')
                        ->required()
                        ->options([
                            'active'   => 'Aktif',
                            'inactive' => 'Tidak Aktif',
                        ])
                        ->default('active'),
                ]),
            Section::make('Penempatan')
                ->columns(2)
                ->components([
                    Select::make('job_role_id')
                        ->label('Jabatan')
                        ->relationship('jobRole', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('branch_id')
                        ->label('Cabang')
                        ->relationship('branch', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('area')
                        ->label('Area')
                        ->required()
                        ->maxLength(50),
                    TextInput::make('region')
                        ->label('Region')
                        ->required()
                        ->maxLength(50),
                ]),
            Section::make('Data Pribadi & Rekam HAV')
                ->columns(2)
                ->components([
                    DatePicker::make('entry_date')
                        ->label('Tanggal Masuk')
                        ->required(),
                    DatePicker::make('date_of_birth')
                        ->label('Tanggal Lahir'),
                    TextInput::make('hav_score')
                        ->label('HAV Score')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(11),
                    TextInput::make('hav_category')
                        ->label('Kategori HAV')
                        ->maxLength(50),
                    TextInput::make('grade')
                        ->label('Grade')
                        ->maxLength(10),
                    TextInput::make('italent_user')
                        ->label('iTalent User')
                        ->maxLength(100),
                ]),
        ]);
    }
}
