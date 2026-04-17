<?php

namespace App\Filament\Resources\CompetencyTracks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompetencyTrackForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Track Kompetensi')
                ->columns(2)
                ->components([
                    TextInput::make('code')
                        ->label('Kode Track')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(30),
                    TextInput::make('name')
                        ->label('Nama Track')
                        ->required()
                        ->maxLength(150),
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
                    TextInput::make('category')
                        ->label('Kategori')
                        ->required()
                        ->maxLength(100),
                    Select::make('level_sequence')
                        ->label('Urutan Level')
                        ->required()
                        ->options([
                            1 => 'Level 1 (Entry)',
                            2 => 'Level 2 (Intermediate)',
                            3 => 'Level 3 (Expert)',
                        ]),
                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->columnSpanFull()
                        ->rows(3),
                ]),
        ]);
    }
}
