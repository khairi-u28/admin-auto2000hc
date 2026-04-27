<?php

namespace App\Filament\Resources\SesiResource\RelationManagers;

use App\Models\Employee;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;

class ParticipantsRelationManager extends RelationManager
{
    protected static string $relationship = 'peserta';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('employee_id')
                ->label('Karyawan')
                ->options(fn () => Employee::query()->orderBy('full_name')->pluck('full_name', 'id'))
                ->searchable()
                ->preload()
                ->required(),
            Select::make('status')
                ->label('Status')
                ->options([
                    'registered' => 'registered',
                    'in_progress' => 'in_progress',
                    'completed' => 'completed',
                    'overdue' => 'overdue',
                ])
                ->default('registered')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee_nrp')->label('NRP')->searchable(),
                TextColumn::make('employee_name')->label('Nama')->searchable(),
                TextColumn::make('employee_position')->label('Jabatan')->searchable(),
                TextColumn::make('employee_branch')->label('Cabang')->searchable(),
                TextColumn::make('status')->label('Status')->badge(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $employee = Employee::with('branch')->find($data['employee_id']);
                        $data['name'] = $employee?->nama_lengkap ?? $employee?->full_name;
                        $data['email'] = null;

                        return $data;
                    }),
            ]);
    }
}
