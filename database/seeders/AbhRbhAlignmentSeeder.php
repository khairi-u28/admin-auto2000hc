<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\JobRole;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class AbhRbhAlignmentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $this->command->info('Aligning ABH and RBH roles...');

        $abhRole = JobRole::where('code', 'ABH01')->first();
        $rbhRole = JobRole::where('code', 'RBH01')->first();

        if (!$abhRole || !$rbhRole) {
            $this->command->error('ABH01 or RBH01 job role not found. Please run JobRoleSeeder first.');
            return;
        }

        // 1. Process Areas
        $areas = Branch::whereNotNull('area')->distinct()->pluck('area');
        foreach ($areas as $areaName) {
            $areaModel = Area::where('nama_area', $areaName)->first();
            
            // Find or create ABH employee for this area
            $abh = Employee::where('job_role_id', $abhRole->id)
                ->where('area', $areaName)
                ->first();

            if (!$abh) {
                // Get a branch in this area to assign the employee to
                $branch = Branch::where('area', $areaName)->first();
                $name = strtoupper($faker->name);
                $nrp = (string) rand(900000, 999999);
                
                $abh = Employee::create([
                    'nrp' => $nrp,
                    'full_name' => $name,
                    'nama_lengkap' => $name,
                    'position_name' => 'Area Business Head',
                    'job_role_id' => $abhRole->id,
                    'branch_id' => $branch->id,
                    'area' => $areaName,
                    'region' => $branch->region,
                    'status' => 'active',
                ]);
            }

            // Ensure user link
            if (!$abh->user_id) {
                $user = User::create([
                    'name' => $abh->nama_lengkap,
                    'email' => strtolower(str_replace(' ', '.', $abh->nama_lengkap)) . "@auto2000.co.id",
                    'password' => Hash::make('password'),
                ]);
                $abh->update(['user_id' => $user->id]);
            }

            // Sync Area model
            if ($areaModel) {
                $areaModel->update(['nama_abh' => $abh->nama_lengkap]);
            }
        }

        // 2. Process Regions
        $regions = Branch::whereNotNull('region')->distinct()->pluck('region');
        foreach ($regions as $regionName) {
            $regionModel = Region::where('nama_region', $regionName)->first();

            // Find or create RBH employee for this region
            $rbh = Employee::where('job_role_id', $rbhRole->id)
                ->where('region', $regionName)
                ->first();

            if (!$rbh) {
                // Get a branch in this region
                $branch = Branch::where('region', $regionName)->first();
                $name = strtoupper($faker->name);
                $nrp = (string) rand(800000, 899999);

                $rbh = Employee::create([
                    'nrp' => $nrp,
                    'full_name' => $name,
                    'nama_lengkap' => $name,
                    'position_name' => 'Region Business Head',
                    'job_role_id' => $rbhRole->id,
                    'branch_id' => $branch->id,
                    'area' => 'HEAD OFFICE',
                    'region' => $regionName,
                    'status' => 'active',
                ]);
            }

            // Ensure user link
            if (!$rbh->user_id) {
                $user = User::create([
                    'name' => $rbh->nama_lengkap,
                    'email' => strtolower(str_replace(' ', '.', $rbh->nama_lengkap)) . "@auto2000.co.id",
                    'password' => Hash::make('password'),
                ]);
                $rbh->update(['user_id' => $user->id]);
            }

            // Sync Region model
            if ($regionModel) {
                $regionModel->update(['nama_rbh' => $rbh->nama_lengkap]);
            }
        }

        $this->command->info('ABH and RBH alignment complete.');
    }
}
