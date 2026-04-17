<?php

namespace App\Filament\Resources\CompetencyTracks\Pages;

use App\Filament\Resources\CompetencyTracks\CompetencyTrackResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCompetencyTracks extends ListRecords
{
    protected static string $resource = CompetencyTrackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
