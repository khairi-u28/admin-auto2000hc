<?php

namespace App\Filament\Resources\Batches\Cabang\Pages;

use App\Filament\Resources\Batches\Cabang\BatchCabangResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateBatchCabang extends CreateRecord
{
    protected static string $resource = BatchCabangResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['type'] = 'cabang';
        $data['created_by'] = Auth::id() ?? \App\Models\User::first()->id;
        
        $data['batch_code'] = \App\Models\Batch::generateCode('CAB', date('Y'));
        
        return $data;
    }
}
