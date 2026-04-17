<?php

namespace App\Filament\Resources\JobRoles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JobRoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Jabatan')
                ->columns(2)
                ->components([
                    TextInput::make('code')
                        ->label('Kode Jabatan')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(20),
                    TextInput::make('name')
                        ->label('Nama Jabatan')
                        ->required()
                        ->maxLength(100),
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
                    TextInput::make('level')
                        ->label('Level')
                        ->required()
                        ->maxLength(50),
                    TextInput::make('category')
                        ->label('Kategori')
                        ->required()
                        ->maxLength(50),
                ]),
        ]);
    }
}
