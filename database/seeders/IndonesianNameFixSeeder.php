<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class IndonesianNameFixSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $this->command->info('Fixing employee names and ensuring user accounts...');

        // 1. Fix employees with "ABH" or "RBH" or null names
        $employeesToFix = Employee::where('nama_lengkap', 'like', 'ABH %')
            ->orWhere('nama_lengkap', 'like', 'RBH %')
            ->orWhereNull('nama_lengkap')
            ->orWhere('nama_lengkap', '')
            ->get();

        foreach ($employeesToFix as $emp) {
            $newName = strtoupper($faker->name);
            $emp->update([
                'nama_lengkap' => $newName,
                'full_name' => $newName,
            ]);
        }

        // 2. Ensure all employees have a linked User account (required for Trainers in the dashboard)
        $employeesWithoutUser = Employee::whereNull('user_id')->get();
        foreach ($employeesWithoutUser as $emp) {
            // Check if user with same email or NRP exists
            $email = strtolower(str_replace(' ', '.', $emp->nama_lengkap)) . "@auto2000.co.id";
            $user = User::where('email', $email)->orWhere('name', $emp->nrp)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $emp->nrp,
                    'email' => $email,
                    'password' => Hash::make('password'),
                ]);
            }

            $emp->update(['user_id' => $user->id]);
        }

        // 3. Update existing Users to have proper names if they match employees
        $employeesWithUser = Employee::whereNotNull('user_id')->get();
        foreach ($employeesWithUser as $emp) {
            $user = User::find($emp->user_id);
            if ($user && ($user->name === $emp->nrp || empty($user->name))) {
                // If the user name is just the NRP, update it to the full name for better UI
                // But wait, the User model uses 'name' for login or display? 
                // Usually 'name' is for display.
                $user->update(['name' => $emp->nama_lengkap]);
            }
        }

        $this->command->info('Employee names fixed and user links synchronized.');
    }
}
