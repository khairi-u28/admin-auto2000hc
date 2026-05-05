<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\User;
use App\Models\Batch;
use App\Models\JobRole;
use App\Models\Competency;
use App\Models\LearningPath;

class DatabaseDiagnosticSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('--- DATABASE DIAGNOSTIC REPORT ---');
        
        $tables = [
            'users' => User::class,
            'employees' => Employee::class,
            'branches' => \App\Models\Branch::class,
            'job_roles' => JobRole::class,
            'competencies' => Competency::class,
            'learning_paths' => LearningPath::class,
            'batches' => Batch::class,
            'batch_participants' => \App\Models\BatchParticipant::class,
            'batch_feedback' => \App\Models\BatchFeedback::class,
        ];

        foreach ($tables as $name => $model) {
            try {
                $count = $model::count();
                $this->command->info("Table '{$name}': {$count} records");
            } catch (\Exception $e) {
                $this->command->error("Table '{$name}': FAILED TO READ - " . $e->getMessage());
            }
        }

        $this->command->info('--- RELATIONSHIP CHECK ---');

        // Check if employees have job roles
        $empWithRole = Employee::whereNotNull('job_role_id')->count();
        $empWithValidRole = Employee::whereIn('job_role_id', JobRole::pluck('id'))->count();
        $this->command->info("Employees with any job_role_id: {$empWithRole}");
        $this->command->info("Employees with VALID job_role_id (found in job_roles table): {$empWithValidRole}");

        // Check if employees have users
        $empWithUser = Employee::whereNotNull('user_id')->count();
        $empWithValidUser = Employee::whereIn('user_id', User::pluck('id'))->count();
        $this->command->info("Employees with any user_id: {$empWithUser}");
        $this->command->info("Employees with VALID user_id (found in users table): {$empWithValidUser}");

        // Check if batches have PICs
        $batchWithPic = Batch::whereNotNull('pic_id')->count();
        $batchWithValidPic = Batch::whereIn('pic_id', User::pluck('id'))->count();
        $this->command->info("Batches with any pic_id: {$batchWithPic}");
        $this->command->info("Batches with VALID pic_id (found in users table): {$batchWithValidPic}");

        // Check if participants have employees
        $partWithValidEmp = DB::table('batch_participants')
            ->whereIn('employee_id', Employee::pluck('id'))->count();
        $this->command->info("Batch Participants with VALID employee_id: {$partWithValidEmp}");

        $this->command->info('--- DATE CHECK ---');
        $minDate = Batch::min('end_date');
        $maxDate = Batch::max('end_date');
        $this->command->info("Batch End Dates range: {$minDate} to {$maxDate}");

        $this->command->info('--- DIAGNOSTIC COMPLETE ---');
    }
}
