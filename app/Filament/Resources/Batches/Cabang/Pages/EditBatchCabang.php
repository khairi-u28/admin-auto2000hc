<?php

namespace App\Filament\Resources\Batches\Cabang\Pages;

use App\Filament\Resources\Batches\Cabang\BatchCabangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBatchCabang extends EditRecord
{
    protected static string $resource = BatchCabangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
