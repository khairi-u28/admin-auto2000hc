<?php

namespace App\Filament\Resources\Batches\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BatchParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'participants';

    protected static ?string $recordTitleAttribute = 'employee_id';

    protected static ?string $title = 'Peserta Batch';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
                Select::make('employee_id')
                    ->label('Karyawan')
                    ->relationship('employee', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'menunggu_undangan' => 'Menunggu Undangan',
                        'diundang' => 'Diundang',
                        'terdaftar' => 'Terdaftar',
                        'hadir' => 'Hadir',
                        'lulus' => 'Lulus',
                        'tidak_lulus' => 'Tidak Lulus',
                        'batal' => 'Batal',
                    ])
                    ->default('menunggu_undangan')
                    ->required(),
                Textarea::make('participant_notes')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('employee_id')
            ->columns([
                TextColumn::make('employee.full_name')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee.nrp')
                    ->label('NRP')
                    ->searchable(),
                TextColumn::make('employee.branch.nama')
                    ->label('Cabang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'gray' => 'menunggu_undangan',
                        'info' => 'diundang',
                        'primary' => 'terdaftar',
                        'warning' => 'hadir',
                        'success' => 'lulus',
                        'danger' => fn ($state) => in_array($state, ['tidak_lulus', 'batal']),
                    ]),
            ])
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




