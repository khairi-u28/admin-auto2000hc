<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\BatchParticipant;
use App\Models\BatchFeedback;
use App\Models\Branch;
use App\Models\Competency;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseEnricherSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Enriching data with more variations...');

        $competencies = Competency::all();
        $branches = Branch::all();
        $employees = Employee::all();
        $users = User::all();

        if ($competencies->isEmpty() || $branches->isEmpty() || $employees->isEmpty()) {
            $this->command->error('Please run initial seeders first (Branch, Competency, Employee).');
            return;
        }

        // Create varied batches for 2024, 2025, 2026
        $years = [2024, 2025, 2026];
        $types = ['HO', 'Cabang'];
        $statuses = ['selesai', 'berlangsung', 'open', 'dibatalkan'];

        foreach ($years as $year) {
            // Increase to 20 batches per year for better variety
            foreach (range(1, 20) as $i) {
                $type = $types[array_rand($types)];
                // Weighted status: more 'selesai' for historical data
                if ($year < 2026) {
                    $status = rand(1, 10) > 2 ? 'selesai' : 'dibatalkan';
                } else {
                    $status = $statuses[array_rand($statuses)];
                }
                
                $comp = $competencies->random();
                $branch = $branches->random();
                
                // Ensure the PIC is a user with an employee profile
                $pic = $users->random();

                $startDate = Carbon::create($year, rand(1, 12), rand(1, 28));
                $endDate = (clone $startDate)->addDays(rand(2, 5));

                $batch = Batch::create([
                    'batch_code' => Batch::generateCode($type),
                    'name' => "Batch {$comp->name} - " . $startDate->format('M Y'),
                    'type' => $type,
                    'competency_id' => $comp->id,
                    'branch_id' => $branch->id,
                    'pic_id' => $pic->id,
                    'created_by' => $users->first()->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'target_participants' => rand(15, 35),
                    'status' => $status,
                    'description' => "Enriched data batch for {$comp->name}.",
                ]);

                // Add participants
                $participantCount = rand(12, 30);
                $batchParticipants = $employees->random($participantCount);

                foreach ($batchParticipants as $emp) {
                    $pStatus = 'lulus';
                    if ($status === 'selesai') {
                        $rand = rand(1, 100);
                        if ($rand > 85) $pStatus = 'tidak_lulus';
                        else $pStatus = 'lulus';
                    } else {
                        $pStatus = 'terdaftar';
                    }

                    BatchParticipant::create([
                        'batch_id' => $batch->id,
                        'employee_id' => $emp->id,
                        'status' => $pStatus,
                    ]);

                    // Add feedback if selesai
                    if ($status === 'selesai' && rand(1, 10) > 2) {
                        BatchFeedback::create([
                            'batch_id' => $batch->id,
                            'employee_id' => $emp->id,
                            'training_relevance' => rand(3, 5),
                            'training_material_quality' => rand(3, 5),
                            'training_schedule' => rand(3, 5),
                            'training_facility' => rand(3, 5),
                            'trainer_mastery' => rand(3, 5),
                            'trainer_delivery' => rand(3, 5),
                            'trainer_responsiveness' => rand(3, 5),
                            'trainer_attitude' => rand(3, 5),
                            'is_submitted' => true,
                            'submitted_at' => (clone $endDate)->addDays(rand(0, 2)),
                        ]);
                    }
                }
            }
        }

        $this->command->info('Data enrichment complete.');
    }
}
