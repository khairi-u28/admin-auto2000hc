<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Imports\EmployeeImporter;
use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(EmployeeImporter::class)
                ->label('Import CSV'),
            Action::make('download_template')
                ->label('Download Template CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $headers = [
                        'nrp',
                        'nama_lengkap',
                        'position_name',
                        'pos',
                        'kode_cabang',
                        'masa_bakti',
                        'status',
                        'job_role_code',
                    ];

                    $content = implode(',', $headers) . PHP_EOL;

                    return response()->streamDownload(
                        fn () => print($content),
                        'template-karyawan.csv',
                        ['Content-Type' => 'text/csv']
                    );
                }),
        ];
    }
}
