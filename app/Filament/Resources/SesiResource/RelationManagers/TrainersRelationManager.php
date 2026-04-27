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

class TrainersRelationManager extends RelationManager
{
    protected static string $relationship = 'trainers';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('employee_id')
                ->label('Trainer')
                ->options(fn () => Employee::query()->orderBy('full_name')->pluck('full_name', 'id'))
                ->searchable()
                ->preload()
                ->required(),
            Select::make('role')
                ->label('Role')
                ->options([
                    'trainer' => 'trainer',
                    'lead_trainer' => 'lead_trainer',
                ])
                ->default('trainer')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trainer_name')->label('Nama')->searchable(),
                TextColumn::make('employee.position_name')->label('Jabatan')->searchable(),
                TextColumn::make('role')->label('Role')->badge()->searchable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $employee = Employee::find($data['employee_id']);
                        $data['name'] = $employee?->nama_lengkap ?? $employee?->full_name;

                        return $data;
                    }),
            ]);
    }
}
