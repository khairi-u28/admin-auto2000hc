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
            Actions\Action::make('save')
                ->label('Simpan Perubahan')
                ->color('primary')
                ->action('save'),
            Actions\Action::make('cancel')
                ->label('Batal')
                ->color('secondary')
                ->url($this->getResource()::getUrl('index')),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
