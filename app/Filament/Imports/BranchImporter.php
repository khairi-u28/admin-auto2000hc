<?php

namespace App\Filament\Imports;

use App\Models\Branch;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class BranchImporter extends Importer
{
    protected static ?string $model = Branch::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->label('Kode Cabang')
                ->requiredMapping()
                ->rules(['required', 'max:20']),
            ImportColumn::make('name')
                ->label('Nama Cabang')
                ->requiredMapping()
                ->rules(['required', 'max:100']),
            ImportColumn::make('region')
                ->label('Region')
                ->rules(['nullable', 'max:50']),
            ImportColumn::make('area')
                ->label('Area')
                ->rules(['nullable', 'max:50']),
            ImportColumn::make('type')
                ->label('Tipe (GR/BP/HO)')
                ->rules(['nullable', 'in:GR,BP,HO']),
        ];
    }

    public function resolveRecord(): ?Branch
    {
        return Branch::firstOrNew(['code' => $this->data['code']]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import cabang selesai: ' . number_format($import->successful_rows) . ' baris berhasil.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal.';
        }

        return $body;
    }
}
