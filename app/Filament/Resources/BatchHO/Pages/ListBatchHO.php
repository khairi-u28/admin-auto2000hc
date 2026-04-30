<?php
namespace App\Filament\Resources\BatchHO\Pages;

use App\Filament\Resources\BatchHO\BatchHOResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBatchHO extends ListRecords
{
    protected static string $resource = BatchHOResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}