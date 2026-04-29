<?php

namespace App\Filament\Resources\Batches\HO\Pages;

use App\Filament\Resources\Batches\HO\BatchHOResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBatchHO extends EditRecord
{
    protected static string $resource = BatchHOResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
