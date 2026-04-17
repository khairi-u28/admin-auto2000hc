<?php

namespace App\Filament\Resources\DevelopmentPrograms\Pages;

use App\Filament\Resources\DevelopmentPrograms\DevelopmentProgramResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDevelopmentProgram extends EditRecord
{
    protected static string $resource = DevelopmentProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
