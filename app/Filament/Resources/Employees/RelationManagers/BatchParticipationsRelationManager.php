<?php
namespace App\Filament\Resources\Employees\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class BatchParticipationsRelationManager extends RelationManager
{
    protected static string $relationship = 'batchParticipations';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('batch_code')
            ->columns([
                Tables\Columns\TextColumn::make('batch.batch_code')
                    ->label('Kode Batch')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('batch.name')
                    ->label('Nama Batch')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('batch.competency.name')
                    ->label('Kompetensi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('batch.type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'HO'     => 'info',
                        'Cabang' => 'success',
                        default  => 'gray',
                    }),

                Tables\Columns\TextColumn::make('batch.start_date')
                    ->label('Tanggal Mulai')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('batch.end_date')
                    ->label('Tanggal Selesai')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('batch.status')
                    ->label('Status Batch')
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

                Tables\Columns\TextColumn::make('status')
                    ->label('Status Peserta')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu_undangan' => 'gray',
                        'diundang'          => 'blue',
                        'terdaftar'         => 'cyan',
                        'sedang_berjalan'   => 'amber',
                        'selesai'           => 'lime',
                        'terlambat'         => 'orange',
                        'lulus'             => 'green',
                        'tidak_lulus'       => 'red',
                        default             => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'menunggu_undangan' => 'Menunggu Undangan',
                        'diundang'          => 'Diundang',
                        'terdaftar'         => 'Terdaftar',
                        'sedang_berjalan'   => 'Sedang Berjalan',
                        'selesai'           => 'Selesai',
                        'terlambat'         => 'Terlambat',
                        'lulus'             => 'Lulus',
                        'tidak_lulus'       => 'Tidak Lulus',
                        default             => $state,
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}