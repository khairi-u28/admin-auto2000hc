<?php

namespace Database\Seeders;

use App\Models\Competency;
use App\Models\JobRole;
use App\Models\LearningPath;
use App\Models\User;
use Illuminate\Database\Seeder;

class LearningPathSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@auto2000.co.id')->first();

        if (! $admin) {
            $this->command->warn('Admin user not found; skipping LearningPathSeeder.');
            return;
        }

        $paths = [
            [
                'name'        => 'Mechanic Trainee Path',
                'job_role_code' => 'MTR01',
                'description' => 'Learning path untuk Mekanik Trainee GR dari level dasar hingga siap naik level.',
                'status'      => 'published',
                'competencies' => ['COMP-MCH-001', 'COMP-GEN-001'],
            ],
            [
                'name'        => 'Sales Consultant Path',
                'job_role_code' => 'SC001',
                'description' => 'Learning path lengkap untuk Sales Consultant dari fundamental hingga advanced.',
                'status'      => 'published',
                'competencies' => ['COMP-SC-001', 'COMP-SC-002', 'COMP-GEN-001'],
            ],
            [
                'name'        => 'Service Advisor Path',
                'job_role_code' => 'SA001',
                'description' => 'Learning path Service Advisor mencakup pelayanan pelanggan dan proses service.',
                'status'      => 'published',
                'competencies' => ['COMP-SA-001', 'COMP-GEN-001'],
            ],
        ];

        foreach ($paths as $data) {
            $jobRole = JobRole::where('code', $data['job_role_code'])->first();

            if (! $jobRole) {
                $this->command->warn("JobRole {$data['job_role_code']} not found; skipping.");
                continue;
            }

            $path = LearningPath::firstOrCreate(
                ['name' => $data['name']],
                [
                    'job_role_id' => $jobRole->id,
                    'description' => $data['description'],
                    'status'      => $data['status'],
                    'created_by'  => $admin->id,
                ]
            );

            // Attach competencies with order
            $competencyIds = [];
            foreach ($data['competencies'] as $index => $code) {
                $competency = Competency::where('code', $code)->first();
                if ($competency) {
                    $competencyIds[$competency->id] = [
                        'id'          => (string) \Illuminate\Support\Str::uuid(),
                        'order_index' => $index,
                        'is_mandatory' => true,
                    ];
                }
            }

            if (! empty($competencyIds)) {
                $path->competencies()->syncWithoutDetaching($competencyIds);
            }
        }
    }
}
