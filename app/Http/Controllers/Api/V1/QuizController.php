<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * GET /api/v1/quiz/{enrollment}
     * Retrieve courses with quiz information for an enrollment.
     */
    public function show(string $enrollmentId)
    {
        $enrollment = Enrollment::with([
            'curriculum.modules.courses',
        ])->findOrFail($enrollmentId);

        $courses = [];
        foreach ($enrollment->curriculum?->modules ?? [] as $module) {
            foreach ($module->courses as $course) {
                $courses[] = [
                    'id'               => $course->id,
                    'title'            => $course->title,
                    'type'             => $course->type,
                    'duration_minutes' => $course->duration_minutes,
                    'module_id'        => $module->id,
                    'module_name'      => $module->name,
                ];
            }
        }

        return response()->json([
            'data' => [
                'enrollment_id' => $enrollment->id,
                'curriculum'    => $enrollment->curriculum ? ['id' => $enrollment->curriculum->id, 'name' => $enrollment->curriculum->name] : null,
                'courses'       => $courses,
            ],
            'meta'    => [],
            'message' => 'success',
        ]);
    }

    /**
     * POST /api/v1/quiz/{enrollment}/submit
     * Submit a quiz attempt for a course within an enrollment.
     * Body: { course_id, score, passed, answers }
     */
    public function submit(Request $request, string $enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);

        $validated = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'score'     => 'required|numeric|min:0|max:100',
            'passed'    => 'required|boolean',
            'answers'   => 'sometimes|array',
        ]);

        $attempt = QuizAttempt::create([
            'enrollment_id' => $enrollment->id,
            'course_id'     => $validated['course_id'],
            'score'         => $validated['score'],
            'passed'        => $validated['passed'],
            'attempted_at'  => now(),
        ]);

        // If passed, check if all courses are done and bump progress
        if ($validated['passed']) {
            $totalCourses    = $enrollment->curriculum?->modules->flatMap->courses->count() ?? 0;
            $passedCourseIds = QuizAttempt::where('enrollment_id', $enrollment->id)
                ->where('passed', true)
                ->distinct('course_id')
                ->pluck('course_id')
                ->count();

            if ($totalCourses > 0) {
                $progressPct = (int) round(($passedCourseIds / $totalCourses) * 100);
                $enrollment->progress_pct = $progressPct;

                if ($progressPct >= 100) {
                    $enrollment->status       = 'completed';
                    $enrollment->completed_at = now();
                }

                $enrollment->save();
            }
        }

        return response()->json([
            'data' => [
                'attempt_id'    => $attempt->id,
                'enrollment_id' => $enrollment->id,
                'course_id'     => $attempt->course_id,
                'score'         => $attempt->score,
                'passed'        => (bool) $attempt->passed,
                'attempted_at'  => $attempt->attempted_at->toDateTimeString(),
                'enrollment_progress_pct' => $enrollment->progress_pct,
                'enrollment_status'       => $enrollment->status,
            ],
            'meta'    => [],
            'message' => 'success',
        ]);
    }
}
