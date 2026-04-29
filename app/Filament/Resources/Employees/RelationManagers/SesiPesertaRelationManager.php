<?php

namespace App\Filament\Resources\Employees\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SesiPesertaRelationManager extends RelationManager
{
    protected static string $relationship = 'sesiPeserta';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'Riwayat Sesi (Legacy)';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('sesi.judul')
                    ->label('Judul Sesi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status_kehadiran')
                    ->label('Kehadiran')
                    ->badge(),
                TextColumn::make('status_kelulusan')
                    ->label('Kelulusan')
                    ->badge(),
                TextColumn::make('sesi.waktu_mulai')
                    ->label('Waktu Sesi')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Read-only
            ])
            ->actions([
                // 
            ])
            ->bulkActions([
                //
            ]);
    }
}

