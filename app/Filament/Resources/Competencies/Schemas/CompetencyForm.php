<?php

namespace App\Filament\Resources\Competencies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompetencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Kompetensi')
                ->columns(2)
                ->components([
                    TextInput::make('code')
                        ->label('Kode Kompetensi')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(50),
                    TextInput::make('name')
                        ->label('Nama Kompetensi')
                        ->required()
                        ->maxLength(255),
                    TagsInput::make('tags')
                        ->label('Tags')
                        ->suggestions([
                            'Sales' => 'Sales',
                            'Aftersales' => 'Aftersales',
                            'HO' => 'HO',
                            'People Development' => 'People Development',
                            'General' => 'General',
                        ])
                        ->columnSpanFull(),
                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
