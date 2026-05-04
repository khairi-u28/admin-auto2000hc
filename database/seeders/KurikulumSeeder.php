<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Curriculum;
use App\Models\Module;
use App\Models\Competency;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KurikulumSeeder extends Seeder
{
    public function run(): void
    {
        $competencies = Competency::all();
        $admin = User::query()
            ->whereIn('email', ['admin@auto2000hc.id', 'admin@auto2000.co.id'])
            ->orderByRaw("FIELD(email, 'admin@auto2000hc.id', 'admin@auto2000.co.id')")
            ->first()
            ?? User::query()->orderBy('id')->first();

        if ($competencies->isEmpty() || !$admin) {
            return;
        }

        foreach ($competencies as $comp) {
            // 1. Create a Curriculum for each competency
            $curriculum = Curriculum::firstOrCreate(
                ['title' => 'Kurikulum ' . $comp->name],
                [
                    'status' => 'published',
                    'department' => 'HC',
                    'academic_year' => date('Y'),
                    'created_by' => $admin->id,
                ]
            );

            // 2. Create Modules
            for ($i = 1; $i <= rand(2, 3); $i++) {
                $module = Module::firstOrCreate(
                    ['title' => 'Modul ' . $i . ': ' . $comp->name],
                    [
                        'description' => 'Modul pembelajaran ' . $i . ' untuk ' . $comp->name,
                        'status' => 'published',
                        'department' => 'HC',
                        'created_by' => $admin->id,
                    ]
                );

                // Link module directly to Competency
                $comp->modules()->syncWithoutDetaching([
                    $module->id => [
                        'id' => (string) Str::uuid(),
                        'order_index' => $i,
                        'is_mandatory' => true,
                    ]
                ]);

                // Link module to Curriculum
                $curriculum->modules()->syncWithoutDetaching([
                    $module->id => [
                        'id' => (string) Str::uuid(),
                        'order_index' => $i,
                        'is_mandatory' => true,
                    ]
                ]);

                // Create 2-3 Courses for each module
                for ($j = 1; $j <= rand(2, 3); $j++) {
                    $course = Course::firstOrCreate(
                        ['title' => 'Materi ' . $i . '.' . $j . ': ' . Str::words($comp->description, 3)],
                        [
                            'description' => 'Detail materi pembelajaran untuk ' . $comp->name,
                            'type' => rand(0, 1) ? 'video' : 'pdf',
                            'duration_minutes' => rand(30, 120),
                            'status' => 'published',
                            'department' => 'HC',
                            'created_by' => $admin->id,
                        ]
                    );

                    // Link course to module
                    $module->courses()->syncWithoutDetaching([
                        $course->id => [
                            'id' => (string) Str::uuid(),
                            'order_index' => $j,
                            'is_mandatory' => true,
                        ],
                    ]);
                }
            }
        }
    }
}
