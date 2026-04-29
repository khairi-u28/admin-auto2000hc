<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\BatchMateri;
use App\Models\BatchParticipant;
use App\Models\Branch;
use App\Models\Competency;
use App\Models\Employee;
use App\Models\User;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Ensure we have at least one user for PIC and created_by
        $admin = User::firstOrCreate(
            ['email' => 'admin@auto2000.co.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        $employees = Employee::all();
        $competencies = Competency::all();
        $branches = Branch::where('type', 'cabang')->get();
        $users = User::all();
        $modules = Module::all();
        $courses = Course::all();

        if ($employees->isEmpty()) {
            return;
        }

        // Create 50 Batches
        for ($i = 0; $i < 50; $i++) {
            $type = $faker->randomElement(['HO', 'Cabang']);
            $status = $faker->randomElement(['draft', 'open', 'berlangsung', 'selesai', 'dibatalkan']);
            $comp = $competencies->random();
            
            $batch = Batch::create([
                'batch_code' => 'BATCH-' . strtoupper($faker->bothify('??###')),
                'name' => 'Training ' . $comp->name . ' Batch ' . ($i + 1),
                'type' => $type,
                'competency_id' => $comp->id,
                'branch_id' => $type === 'Cabang' ? ($branches->isNotEmpty() ? $branches->random()->id : null) : null,
                'pic_id' => $users->random()->id,
                'status' => $status,
                'start_date' => $faker->dateTimeBetween('-6 months', '+1 month'),
                'end_date' => $faker->dateTimeBetween('+1 month', '+2 months'),
                'target_participants' => $faker->numberBetween(20, 50),
                'evaluation_notes' => $status === 'selesai' ? $faker->paragraph : null,
                'created_by' => $admin->id,
            ]);

            // Add 15-40 participants to each batch
            $participantCount = $faker->numberBetween(15, 40);
            $selectedEmployees = $employees->random(min($participantCount, $employees->count()));

            foreach ($selectedEmployees as $employee) {
                BatchParticipant::create([
                    'batch_id' => $batch->id,
                    'employee_id' => $employee->id,
                    'status' => $status === 'selesai' ? 'lulus' : $faker->randomElement(['menunggu_undangan', 'diundang', 'terdaftar', 'sedang_berjalan']),
                    'invitation_sent_at' => $faker->dateTimeBetween('-1 month', 'now'),
                    'completed_at' => $status === 'selesai' ? $batch->end_date : null,
                ]);
            }

            // Add some materials if modules and courses exist
            if ($modules->isNotEmpty() && $courses->isNotEmpty()) {
                for ($j = 0; $j < 3; $j++) {
                    BatchMateri::create([
                        'batch_id' => $batch->id,
                        'module_id' => $modules->random()->id,
                        'course_id' => $courses->random()->id,
                        'order_index' => $j,
                        'session_datetime' => $faker->dateTimeBetween($batch->start_date, $batch->end_date),
                    ]);
                }
            }
        }
    }
}
