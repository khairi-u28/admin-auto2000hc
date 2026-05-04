<?php

namespace Database\Seeders;

use App\Models\Competency;
use App\Models\JobRole;
use App\Models\RoleCompetencyRequirement;
use Illuminate\Database\Seeder;

class RoleCompetencyRequirementSeeder extends Seeder
{
    public function run(): void
    {
        $competencies = Competency::query()->get(['id']);
        $jobRoles = JobRole::query()->get(['id']);

        if ($competencies->isEmpty() || $jobRoles->isEmpty()) {
            return;
        }

        foreach ($jobRoles as $jobRole) {
            $selected = $competencies->random(min($competencies->count(), rand(3, 6)));

            foreach ($selected as $competency) {
                RoleCompetencyRequirement::firstOrCreate(
                    [
                        'job_role_id' => $jobRole->id,
                        'competency_id' => $competency->id,
                    ],
                    [
                        'is_mandatory' => (bool) random_int(0, 1),
                        'minimum_level' => random_int(1, 3),
                    ]
                );
            }
        }
    }
}

