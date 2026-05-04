<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\BatchFeedback;
use App\Models\ParticipantMateriProgress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BatchProgressAndFeedbackSeeder extends Seeder
{
    public function run(): void
    {
        $batches = Batch::query()
            ->with(['materi', 'participants'])
            ->get();

        foreach ($batches as $batch) {
            $courseIds = $batch->materi->pluck('course_id')->unique()->filter()->values();

            if ($courseIds->isEmpty()) {
                continue;
            }

            foreach ($batch->participants as $participant) {
                foreach ($courseIds as $courseId) {
                    $completed = (bool) random_int(0, 1);
                    $score = random_int(50, 100);

                    ParticipantMateriProgress::firstOrCreate(
                        [
                            'batch_id' => $batch->id,
                            'employee_id' => $participant->employee_id,
                            'course_id' => $courseId,
                        ],
                        [
                            'is_completed' => $completed,
                            'quiz_score' => $completed ? $score : null,
                            'quiz_passed' => $completed ? ($score >= 70) : null,
                            'completed_at' => $completed ? Carbon::now()->subDays(random_int(0, 45)) : null,
                        ]
                    );
                }

                // Feedback for ~50% of participants
                if (random_int(1, 100) <= 50) {
                    BatchFeedback::firstOrCreate(
                        [
                            'batch_id' => $batch->id,
                            'employee_id' => $participant->employee_id,
                        ],
                        [
                            'training_relevance' => random_int(3, 5),
                            'training_material_quality' => random_int(3, 5),
                            'training_schedule' => random_int(3, 5),
                            'training_facility' => random_int(3, 5),
                            'training_comments' => 'Feedback ' . Str::upper(Str::random(6)),
                            'trainer_mastery' => random_int(3, 5),
                            'trainer_delivery' => random_int(3, 5),
                            'trainer_responsiveness' => random_int(3, 5),
                            'trainer_attitude' => random_int(3, 5),
                            'trainer_comments' => 'Trainer ' . Str::upper(Str::random(6)),
                            'is_submitted' => true,
                            'submitted_at' => Carbon::now()->subDays(random_int(0, 30)),
                        ]
                    );
                }
            }
        }
    }
}

