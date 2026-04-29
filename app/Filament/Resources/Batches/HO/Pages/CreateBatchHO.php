<?php

namespace App\Filament\Resources\Batches\HO\Pages;

use App\Filament\Resources\Batches\HO\BatchHOResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateBatchHO extends CreateRecord
{
    protected static string $resource = BatchHOResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = 'ho';
        $data['created_by'] = Auth::id() ?? \App\Models\User::first()->id;
        
        $data['batch_code'] = \App\Models\Batch::generateCode('HO', date('Y'));
        
        return $data;
    }
}
