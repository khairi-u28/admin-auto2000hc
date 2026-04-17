<?php

namespace App\Filament\Resources\Branches\Pages;

use App\Filament\Imports\BranchImporter;
use App\Filament\Resources\Branches\BranchResource;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListBranches extends ListRecords
{
    protected static string $resource = BranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(BranchImporter::class)
                ->label('Import dari CSV'),
        ];
    }
}
