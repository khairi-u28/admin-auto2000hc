<?php

namespace App\Filament\Resources\CompetencyTracks\Pages;

use App\Filament\Resources\CompetencyTracks\CompetencyTrackResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompetencyTrack extends EditRecord
{
    protected static string $resource = CompetencyTrackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
