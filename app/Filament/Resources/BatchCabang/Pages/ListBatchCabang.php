<?php
namespace App\Filament\Resources\BatchCabang\Pages;

use App\Filament\Resources\BatchCabang\BatchCabangResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBatchCabang extends ListRecords
{
    protected static string $resource = BatchCabangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}