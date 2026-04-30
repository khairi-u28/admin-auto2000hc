<?php
namespace App\Filament\Resources\BatchHO\Pages;

use App\Filament\Resources\BatchHO\BatchHOResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateBatchHO extends CreateRecord
{
    protected static string $resource = BatchHOResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = 'HO';
        $data['created_by'] = Auth::id();

        return $data;
    }
}