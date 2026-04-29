<?php

namespace App\Filament\Resources\Batches\Cabang\Pages;

use App\Filament\Resources\Batches\Cabang\BatchCabangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListBatchCabang extends ListRecords
{
    protected static string $resource = BatchCabangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
