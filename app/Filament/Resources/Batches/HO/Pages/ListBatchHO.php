<?php

namespace App\Filament\Resources\Batches\HO\Pages;

use App\Filament\Resources\Batches\HO\BatchHOResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListBatchHO extends ListRecords
{
    protected static string $resource = BatchHOResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
