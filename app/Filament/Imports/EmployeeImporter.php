<?php

namespace App\Filament\Imports;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\JobRole;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class EmployeeImporter extends Importer
{
    protected static ?string $model = Employee::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nrp')
                ->label('NRP')
                ->requiredMapping()
                ->rules(['required', 'max:20']),
            ImportColumn::make('nama_lengkap')
                ->label('Nama Lengkap')
                ->requiredMapping()
                ->rules(['required', 'max:150'])
                ->fillRecordUsing(function (Employee $record, string $state): void {
                    $record->nama_lengkap = $state;
                    $record->full_name = $state;
                }),
            ImportColumn::make('position_name')
                ->label('Position Name')
                ->requiredMapping()
                ->rules(['required', 'max:100']),
            ImportColumn::make('pos')
                ->label('POS')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('kode_cabang')
                ->label('Kode Cabang')
                ->requiredMapping()
                ->fillRecordUsing(function (Employee $record, string $state): void {
                    $branch = Branch::query()
                        ->where('kode_cabang', $state)
                        ->orWhere('code', $state)
                        ->first();

                    if ($branch) {
                        $record->branch_id = $branch->id;
                        $record->area = $branch->areaRelation?->nama_area ?? $branch->area;
                        $record->region = $branch->regionRelation?->nama_region ?? $branch->region;
                    }
                }),
            ImportColumn::make('masa_bakti')
                ->label('Masa Bakti')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('status')
                ->label('Status')
                ->requiredMapping()
                ->fillRecordUsing(function (Employee $record, string $state): void {
                    $normalized = strtolower(trim($state));

                    $record->status = match ($normalized) {
                        'aktif', 'active' => 'active',
                        'non_aktif', 'inactive' => 'inactive',
                        default => 'active',
                    };
                }),
            ImportColumn::make('job_role_code')
                ->label('Kode Jabatan')
                ->fillRecordUsing(function (Employee $record, ?string $state): void {
                    if (blank($state)) {
                        return;
                    }

                    $jobRole = JobRole::where('code', $state)->first();

                    if ($jobRole) {
                        $record->job_role_id = $jobRole->id;
                    }
                }),
        ];
    }

    public function resolveRecord(): ?Employee
    {
        return Employee::firstOrNew([
            'nrp' => $this->data['nrp'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import karyawan selesai: ' . number_format($import->successful_rows) . ' baris berhasil.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal.';
        }

        return $body;
    }
}
