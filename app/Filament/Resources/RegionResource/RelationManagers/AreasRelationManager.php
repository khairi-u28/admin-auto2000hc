<?php

namespace App\Filament\Resources\RegionResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AreasRelationManager extends RelationManager
{
    protected static string $relationship = 'areas';

    protected static ?string $title = 'Daftar Area';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('branches'))
            ->columns([
                TextColumn::make('nama_area')
                    ->label('Nama Area')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_abh')
                    ->label('Nama ABH')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('branches_count')
                    ->label('Jml. Cabang')
                    ->sortable(),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make()
                    ->url(fn ($record): string => \App\Filament\Resources\AreaResource::getUrl('view', ['record' => $record])),
            ]);
    }
}
