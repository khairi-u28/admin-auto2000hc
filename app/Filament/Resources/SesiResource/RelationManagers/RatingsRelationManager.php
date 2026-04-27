<?php

namespace App\Filament\Resources\SesiResource\RelationManagers;

use App\Models\Employee;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RatingsRelationManager extends RelationManager
{
    protected static string $relationship = 'ratings';

    protected static ?string $title = 'Review Batch';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('employee_id')
                ->label('Reviewer')
                ->options(fn () => Employee::query()->orderBy('full_name')->pluck('full_name', 'id'))
                ->searchable()
                ->preload(),
            Select::make('rating')
                ->label('Rating')
                ->options([
                    1 => '1',
                    2 => '2',
                    3 => '3',
                    4 => '4',
                    5 => '5',
                ])
                ->required(),
            Textarea::make('comment')
                ->label('Catatan')
                ->rows(3),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.nama_lengkap')->label('Reviewer')->placeholder('-'),
                TextColumn::make('rating')->label('Rating')->badge(),
                TextColumn::make('comment')->label('Catatan')->limit(60)->placeholder('-'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
