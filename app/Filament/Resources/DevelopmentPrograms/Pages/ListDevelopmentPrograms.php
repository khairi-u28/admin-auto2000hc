<?php

namespace App\Filament\Resources\DevelopmentPrograms\Pages;

use App\Filament\Resources\DevelopmentPrograms\DevelopmentProgramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDevelopmentPrograms extends ListRecords
{
    protected static string $resource = DevelopmentProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
