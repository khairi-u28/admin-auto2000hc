<?php

namespace App\Filament\Resources\Batches\RelationManagers;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BatchMateriRelationManager extends RelationManager
{
    protected static string $relationship = 'materi';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'Silabus';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
                Select::make('module_id')
                    ->label('Modul')
                    ->relationship('module', 'title')
                    ->searchable()
                    ->preload(),
                Select::make('course_id')
                    ->label('Materi')
                    ->relationship('course', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('order_index')
                    ->label('Urutan')
                    ->numeric()
                    ->default(1)
                    ->required(),
                DateTimePicker::make('session_datetime')
                    ->label('Waktu Sesi')
                    ->required(),
                TextInput::make('session_venue')
                    ->label('Lokasi/Ruangan')
                    ->maxLength(255),
                TextInput::make('session_link')
                    ->label('Link Online (Zoom/Meet)')
                    ->url()
                    ->maxLength(255),
                Textarea::make('session_notes')
                    ->label('Catatan Sesi')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('order_index')
                    ->label('Urutan')
                    ->sortable(),
                TextColumn::make('module.title')
                    ->label('Modul')
                    ->sortable()
                    ->wrap(),
                TextColumn::make('course.title')
                    ->label('Materi')
                    ->sortable()
                    ->wrap(),
                TextColumn::make('session_datetime')
                    ->label('Waktu Sesi')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('session_venue')
                    ->label('Lokasi')
                    ->limit(20),
                TextColumn::make('session_notes')
                    ->label('Catatan')
                    ->limit(30)
                    ->wrap(),
            ])
            ->defaultSort('order_index', 'asc')
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}




