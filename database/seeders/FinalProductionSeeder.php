<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FinalProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RegionsAndAreasSeeder::class,
            BranchSeeder::class,
            BranchHierarchySeeder::class,
            CompetencySeeder::class,
            JobRoleSeeder::class,
            KurikulumSeeder::class,
            LearningPathSeeder::class,
            EmployeeSeeder::class,
            DemoSeeder::class,
            TrainingRecordSeeder::class,
        ]);
    }
}
