<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Materi')
                ->columns(2)
                ->components([
                    TextInput::make('title')
                        ->label('Judul')
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
                    Select::make('type')
                        ->label('Tipe')
                        ->required()
                        ->live()
                        ->options([
                            'video'           => 'Video',
                            'pdf'             => 'PDF',
                            'article'         => 'Artikel',
                            'quiz'            => 'Quiz',
                            'offline_session' => 'Sesi Offline',
                            'online_session'  => 'Sesi Online',
                        ]),
                    Select::make('status')
                        ->label('Status')
                        ->required()
                        ->options([
                            'draft'     => 'Draft',
                            'published' => 'Published',
                        ])
                        ->default('draft'),
                    TextInput::make('duration_minutes')
                        ->label('Durasi (menit)')
                        ->numeric()
                        ->minValue(1),
                    RichEditor::make('description')
                        ->label('Deskripsi')
                        ->columnSpanFull()
                        ->toolbarButtons([
                            'bold', 'italic', 'underline',
                            'bulletList', 'orderedList',
                            'link', 'undo', 'redo',
                        ]),
                ]),
            Section::make('Sumber Konten')
                ->columns(2)
                ->components([
                    FileUpload::make('file_path')
                        ->label('Upload File')
                        ->acceptedFileTypes(['application/pdf', 'video/mp4', 'video/webm'])
                        ->maxSize(102400)
                        ->directory('courses')
                        ->visible(fn (Get $get): bool => in_array($get('type'), ['pdf', 'video']))
                        ->columnSpanFull(),
                    TextInput::make('external_url')
                        ->label('URL Eksternal')
                        ->url()
                        ->maxLength(500)
                        ->visible(fn (Get $get): bool => in_array($get('type'), ['article', 'online_session']))
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
