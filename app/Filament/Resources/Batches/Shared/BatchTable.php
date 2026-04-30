<?php
namespace App\Filament\Resources\Batches\Shared;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BatchTable
{
    public static function configure(Table $table, string $type): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('batch_code')
                    ->label('Kode Batch')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                TextColumn::make('name')
                    ->label('Nama Batch')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                TextColumn::make('competency.name')
                    ->label('Kompetensi')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft'       => 'gray',
                        'open'        => 'info',
                        'berlangsung' => 'warning',
                        'selesai'     => 'success',
                        'dibatalkan'  => 'danger',
                        default       => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft'       => 'Draft',
                        'open'        => 'Open',
                        'berlangsung' => 'Berlangsung',
                        'selesai'     => 'Selesai',
                        'dibatalkan'  => 'Dibatalkan',
                        default       => $state,
                    }),

                TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Selesai')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('participants_summary')
                    ->label('Peserta (Aktual/Target)')
                    ->state(fn ($record) => 
                        ($record->actual_participants_count ?? 0) . ' / ' . ($record->target_participants ?? 0)
                    ),

                TextColumn::make('pic.name')
                    ->label('PIC')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft'       => 'Draft',
                        'open'        => 'Open',
                        'berlangsung' => 'Berlangsung',
                        'selesai'     => 'Selesai',
                        'dibatalkan'  => 'Dibatalkan',
                    ]),

                SelectFilter::make('competency_id')
                    ->label('Kompetensi')
                    ->relationship('competency', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}