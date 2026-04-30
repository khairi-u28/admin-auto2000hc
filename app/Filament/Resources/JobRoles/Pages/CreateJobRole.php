<?php

namespace App\Filament\Resources\JobRoles\Pages;

use App\Filament\Resources\JobRoles\JobRoleResource;
use App\Models\LearningPath;
use App\Models\User;
use App\Models\JobRole;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateJobRole extends CreateRecord
{
    protected static string $resource = JobRoleResource::class;

    protected function afterCreate(): void
    {
        $jobRole = $this->getRecord();

        if (! $jobRole instanceof JobRole) {
            return;
        }

        $createdBy = Auth::id()
            ?? Auth::user()?->id
            ?? User::query()
                ->whereIn('email', ['admin@auto2000hc.id', 'admin@auto2000.co.id'])
                ->orderByRaw("FIELD(email, 'admin@auto2000hc.id', 'admin@auto2000.co.id')")
                ->value('id');

        if (! $createdBy) {
            return;
        }

        LearningPath::firstOrCreate(
            ['job_role_id' => $jobRole->id],
            [
                'name' => $jobRole->name . ' Path',
                'description' => 'Learning path untuk jabatan ' . $jobRole->name . '.',
                'status' => 'draft',
                'created_by' => $createdBy,
            ]
        );
    }
}
