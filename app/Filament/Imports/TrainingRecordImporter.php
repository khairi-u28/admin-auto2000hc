<?php

namespace App\Filament\Imports;

use App\Models\CompetencyTrack;
use App\Models\Employee;
use App\Models\TrainingRecord;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Carbon\Carbon;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Auth;

class TrainingRecordImporter extends Importer
{
    protected static ?string $model = TrainingRecord::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('employee_nrp')
                ->label('NRP Karyawan')
                ->requiredMapping()
                ->fillRecordUsing(function (TrainingRecord $record, string $state): void {
                    $employee = Employee::where('nrp', $state)->first();
                    if ($employee) {
                        $record->employee_id = $employee->id;
                    }
                }),
            ImportColumn::make('competency_code')
                ->label('Kode Kompetensi')
                ->requiredMapping()
                ->fillRecordUsing(function (TrainingRecord $record, string $state): void {
                    $track = CompetencyTrack::where('code', $state)->first();
                    if ($track) {
                        $record->competency_track_id = $track->id;
                    }
                }),
            ImportColumn::make('level_achieved')
                ->label('Level Dicapai (0-3)')
                ->numeric()
                ->rules(['required', 'integer', 'between:0,3']),
            ImportColumn::make('completion_date')
                ->label('Tanggal Selesai (YYYY-MM-DD atau DD/MM/YYYY)')
                ->rules(['required', 'date_format:Y-m-d,d/m/Y'])
                ->fillRecordUsing(function (TrainingRecord $record, string $state): void {
                    // Normalize incoming date formats to Y-m-d
                    $date = null;
                    if (str_contains($state, '/')) {
                        // Expecting d/m/Y
                        try {
                            $date = Carbon::createFromFormat('d/m/Y', $state);
                        } catch (\Exception $e) {
                            $date = null;
                        }
                    } else {
                        try {
                            $date = Carbon::createFromFormat('Y-m-d', $state);
                        } catch (\Exception $e) {
                            $date = null;
                        }
                    }

                    if ($date) {
                        $record->completion_date = $date->format('Y-m-d');
                    }
                }),
            ImportColumn::make('certification_number')
                ->label('No. Sertifikat')
                ->rules(['nullable', 'max:100']),
            ImportColumn::make('notes')
                ->label('Catatan')
                ->rules(['nullable']),
        ];
    }

    public function resolveRecord(): ?TrainingRecord
    {
        $record = new TrainingRecord();
        $record->source        = 'import';
        $record->recorded_by   = Auth::id();

        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import rekam pelatihan selesai: ' . number_format($import->successful_rows) . ' baris berhasil.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal.';
        }

        return $body;
    }
}
