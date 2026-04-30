<?php
namespace App\Filament\Resources\BatchCabang\Pages;

use App\Filament\Resources\BatchCabang\BatchCabangResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateBatchCabang extends CreateRecord
{
    protected static string $resource = BatchCabangResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = 'Cabang';
        $data['created_by'] = Auth::id();

        return $data;
    }
}