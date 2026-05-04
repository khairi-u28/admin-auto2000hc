<?php

namespace App\Filament\Resources\Branches\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BranchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_cabang')
                    ->label('Kode Cabang')
                    ->getStateUsing(fn($record) => $record->kode_cabang)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama')
                    ->label('Nama Cabang')
                    ->getStateUsing(fn($record) => $record->nama)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('region_label')
                    ->label('Region')
                    ->getStateUsing(fn($record) => $record->regionRelation?->nama_region ?? $record->region)
                    ->sortable(),
                TextColumn::make('area_label')
                    ->label('Area')
                    ->getStateUsing(fn($record) => $record->areaRelation?->nama_area ?? $record->area)
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'VSP' => 'primary',
                        'V' => 'info',
                        'SP' => 'warning',
                        'PDC' => 'success',
                        'HO' => 'gray',
                        'GSO' => 'danger',
                        'CAO' => 'secondary',
                        'Fleet' => 'primary',
                        'BP' => 'warning',
                        default => 'secondary',
                    }),
                TextColumn::make('employees_count')
                    ->label('Jml. Karyawan')
                    ->counts('employees')
                    ->sortable(),
                // TextColumn::make('active_batches')
                //     ->label('Batch Aktif')
                //     ->state(function ($record): int {
                //         try {
                //             return \App\Models\Batch::where('branch_id', $record->id)
                //                 ->whereIn('status',['open','berlangsung'])->count();
                //         } catch (\Exception $e) { return 0; }
                //     }),

                // TextColumn::make('kelulusan_pct')
                //     ->label('Kelulusan %')
                //     ->state(function ($record): string {
                //         try {
                //             $batchIds = \App\Models\Batch::where('branch_id',$record->id)
                //                 ->pluck('id');
                //             $lulus  = \App\Models\BatchParticipant::whereIn('batch_id',$batchIds)
                //                 ->where('status','lulus')->count();
                //             $eval   = \App\Models\BatchParticipant::whereIn('batch_id',$batchIds)
                //                 ->whereIn('status',['lulus','tidak_lulus'])->count();
                //             return $eval > 0 ? round($lulus/$eval*100,1).'%' : '-';
                //         } catch (\Exception $e) { return '-'; }
                //     })
                //     ->badge()
                //     ->color(function ($record): string {
                //         try {
                //             $batchIds = \App\Models\Batch::where('branch_id',$record->id)
                //                 ->pluck('id');
                //             $lulus  = \App\Models\BatchParticipant::whereIn('batch_id',$batchIds)
                //                 ->where('status','lulus')->count();
                //             $eval   = \App\Models\BatchParticipant::whereIn('batch_id',$batchIds)
                //                 ->whereIn('status',['lulus','tidak_lulus'])->count();
                //             $pct = $eval > 0 ? $lulus/$eval*100 : 0;
                //             return $pct >= 70 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
                //         } catch (\Exception $e) { return 'gray'; }
                //     }),
            ])
            ->recordUrl(fn($record): string => \App\Filament\Resources\Branches\BranchResource::getUrl('view', ['record' => $record]))
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'VSP' => 'VSP',
                        'V' => 'V',
                        'SP' => 'SP',
                        'PDC' => 'PDC',
                        'HO' => 'HO',
                        'GSO' => 'GSO',
                        'CAO' => 'CAO',
                        'Fleet' => 'Fleet',
                        'BP' => 'BP',
                    ]),
                SelectFilter::make('region')
                    ->label('Region')
                    ->options(fn() => \App\Models\Branch::distinct()->pluck('region', 'region')->filter()->toArray()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
