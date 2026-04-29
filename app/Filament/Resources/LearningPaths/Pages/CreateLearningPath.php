<?php

namespace App\Filament\Resources\LearningPaths\Pages;

use App\Filament\Resources\LearningPaths\LearningPathResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateLearningPath extends CreateRecord
{
    protected static string $resource = LearningPathResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id() ?? \App\Models\User::first()->id;
        return $data;
    }
}
