<?php

namespace App\Filament\Resources\LearningPaths\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class LearningPathForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Learning Path')
                ->columns(2)
                ->components([
                    TextInput::make('name')
                        ->label('Nama Learning Path')
                        ->required()
                        ->maxLength(255),
                    Select::make('job_role_id')
                        ->label('Jabatan')
                        ->relationship('jobRole', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'archived' => 'Archived',
                        ])
                        ->default('draft')
                        ->required(),
                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
