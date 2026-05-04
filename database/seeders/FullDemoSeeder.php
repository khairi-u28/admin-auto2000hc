<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FullDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RolesAndPermissionsSeeder::class,

            RegionsAndAreasSeeder::class,
            BranchSeeder::class,

            CompetencySeeder::class,
            JobRoleSeeder::class,
            RoleCompetencyRequirementSeeder::class,

            // Creates curricula, modules, courses + pivots
            KurikulumSeeder::class,

            LearningPathSeeder::class,
            EmployeeSeeder::class,
            EmployeeUserLinkSeeder::class,

            TrainingRecordSeeder::class,
            DevelopmentProgramSeeder::class,

            // Creates batches, participants, materi
            DemoSeeder::class,
            BatchProgressAndFeedbackSeeder::class,
        ]);
    }
}

