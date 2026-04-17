<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Cabang')
                ->columns(2)
                ->components([
                    TextInput::make('code')
                        ->label('Kode Cabang')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(20),
                    TextInput::make('name')
                        ->label('Nama Cabang')
                        ->required()
                        ->maxLength(100),
                    TextInput::make('region')
                        ->label('Region')
                        ->required()
                        ->maxLength(50),
                    TextInput::make('area')
                        ->label('Area')
                        ->required()
                        ->maxLength(50),
                    Select::make('type')
                        ->label('Tipe')
                        ->required()
                        ->options([
                            'GR' => 'GR (General Repair)',
                            'BP' => 'BP (Body & Paint)',
                            'HO' => 'HO (Head Office)',
                        ]),
                ]),
        ]);
    }
}
