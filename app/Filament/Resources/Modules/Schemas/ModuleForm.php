<?php

namespace App\Filament\Resources\Modules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Modul')
                ->columns(2)
                ->components([
                    TextInput::make('title')
                        ->label('Judul Modul')
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
                    Select::make('status')
                        ->label('Status')
                        ->required()
                        ->options([
                            'draft'     => 'Draft',
                            'published' => 'Published',
                        ])
                        ->default('draft'),
                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->columnSpanFull()
                        ->rows(3),
                ]),
        ]);
    }
}
