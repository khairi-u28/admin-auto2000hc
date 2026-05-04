<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeUserLinkSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::query()
            ->whereNull('user_id')
            ->limit(50)
            ->get();

        foreach ($employees as $employee) {
            $email = 'user' . $employee->nrp . '@auto2000hc.local';

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $employee->nama_lengkap ?? $employee->full_name ?? ('User ' . $employee->nrp),
                    'password' => Hash::make('password'),
                ]
            );

            $employee->user_id = $user->id;
            $employee->save();
        }
    }
}

