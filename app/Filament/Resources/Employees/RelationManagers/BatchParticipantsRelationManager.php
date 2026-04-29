<?php

namespace App\Filament\Resources\Employees\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BatchParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'batchParticipants';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'Riwayat Batch Training';

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
                TextColumn::make('batch.batch_code')
                    ->label('Kode Batch')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('batch.name')
                    ->label('Nama Batch')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('batch.type')
                    ->label('Tipe')
                    ->badge()
                    ->colors([
                        'primary' => 'ho',
                        'success' => 'cabang',
                    ]),
                TextColumn::make('status')
                    ->label('Status Kepesertaan')
                    ->badge()
                    ->colors([
                        'gray' => 'menunggu_undangan',
                        'info' => 'diundang',
                        'primary' => 'terdaftar',
                        'warning' => 'hadir',
                        'success' => 'lulus',
                        'danger' => fn ($state) => in_array($state, ['tidak_lulus', 'batal']),
                    ]),
                TextColumn::make('batch.start_date')
                    ->label('Tanggal Mulai')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Read-only for now from employee profile
            ])
            ->actions([
                // 
            ])
            ->bulkActions([
                //
            ]);
    }
}

