<?php

namespace App\Filament\Resources\Batches\Shared;

use App\Models\Batch;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;

class BatchSchemas
{
    public static function form(Schema $schema, string $type): Schema
    {
        return $schema->components([
            Section::make('Informasi Batch')
                ->columns(2)
                ->components([
                    TextInput::make('batch_code')
                        ->label('Kode Batch')
                        ->disabled()
                        ->dehydrated(false)
                        ->placeholder('Dibuat otomatis')
                        ->columnSpan(2),
                    TextInput::make('name')
                        ->label('Nama Batch')
                        ->required()
                        ->maxLength(255),
                    Select::make('competency_id')
                        ->label('Kompetensi')
                        ->relationship('competency', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('branch_id')
                        ->label('Cabang Penyelenggara')
                        ->relationship('branch', 'nama')
                        ->searchable()
                        ->preload()
                        ->required(fn () => $type === 'cabang')
                        ->visible(fn () => $type === 'cabang'),
                    Select::make('pic_id')
                        ->label('PIC')
                        ->relationship('pic', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Draft',
                            'pendaftaran' => 'Pendaftaran',
                            'berjalan' => 'Berjalan',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                        ])
                        ->default('draft')
                        ->required(),
                    DatePicker::make('start_date')
                        ->label('Tanggal Mulai')
                        ->required(),
                    DatePicker::make('end_date')
                        ->label('Tanggal Selesai')
                        ->required(),
                    TextInput::make('target_participants')
                        ->label('Target Peserta')
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                    Textarea::make('evaluation_notes')
                        ->label('Catatan Evaluasi')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table, string $type): Table
    {
        return $table
            ->columns([
                TextColumn::make('batch_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('competency.name')
                    ->label('Kompetensi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('branch.nama')
                    ->label('Cabang')
                    ->searchable()
                    ->sortable()
                    ->visible($type === 'cabang'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'primary' => 'draft',
                        'warning' => 'pendaftaran',
                        'info' => 'berjalan',
                        'success' => 'selesai',
                        'danger' => 'dibatalkan',
                    ]),
                TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('target_participants')
                    ->label('Peserta (Aktual/Target)')
                    ->state(fn (Batch $record): string => "{$record->aktual_peserta} / {$record->target_participants}"),
            ])
            ->filters([
                SelectFilter::make('competency_id')
                    ->label('Kompetensi')
                    ->relationship('competency', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'pendaftaran' => 'Pendaftaran',
                        'berjalan' => 'Berjalan',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('publish')
                    ->label('Buka Pendaftaran')
                    ->icon('heroicon-o-check-circle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(fn (Batch $record) => $record->update(['status' => 'pendaftaran']))
                    ->visible(fn (Batch $record) => $record->status === 'draft'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}



