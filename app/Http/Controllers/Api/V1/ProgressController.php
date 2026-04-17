<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    /**
     * GET /api/v1/progress
     * List enrollments (optionally filtered by employee).
     */
    public function index(Request $request)
    {
        $query = Enrollment::with(['employee:id,nrp,full_name', 'curriculum:id,name,academic_year'])
            ->when(
                $request->filled('employee_id'),
                fn ($q) => $q->where('employee_id', $request->employee_id)
            )
            ->when(
                $request->filled('status'),
                fn ($q) => $q->where('status', $request->status)
            );

        // Non-admin users only see their own records
        /** @var User $user */
        $user = Auth::user();
        if (! $user->hasRole('super_admin')) {
            $employeeId = $user->employee?->id;
            if ($employeeId) {
                $query->where('employee_id', $employeeId);
            }
        }

        $perPage  = min((int) $request->input('per_page', 15), 100);
        $paginate = $query->paginate($perPage);

        return response()->json([
            'data' => $paginate->map(fn (Enrollment $e) => [
                'id'            => $e->id,
                'employee'      => $e->employee ? ['id' => $e->employee->id, 'nrp' => $e->employee->nrp, 'full_name' => $e->employee->full_name] : null,
                'curriculum'    => $e->curriculum ? ['id' => $e->curriculum->id, 'name' => $e->curriculum->name, 'academic_year' => $e->curriculum->academic_year] : null,
                'status'        => $e->status,
                'progress_pct'  => $e->progress_pct,
                'enrolled_at'   => $e->enrolled_at?->toDateString(),
                'completed_at'  => $e->completed_at?->toDateString(),
            ]),
            'meta' => [
                'total'        => $paginate->total(),
                'per_page'     => $paginate->perPage(),
                'current_page' => $paginate->currentPage(),
                'last_page'    => $paginate->lastPage(),
            ],
            'message' => 'success',
        ]);
    }

    /**
     * PATCH /api/v1/progress/{enrollment}/update
     * Update progress for an enrollment.
     */
    public function update(Request $request, string $enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);

        $validated = $request->validate([
            'progress_pct' => 'sometimes|integer|min:0|max:100',
            'status'       => 'sometimes|string|in:active,completed,dropped',
            'completed_at' => 'sometimes|nullable|date',
        ]);

        $enrollment->fill($validated);

        // Auto-set completed_at when status goes to completed
        if (($validated['status'] ?? null) === 'completed' && ! $enrollment->completed_at) {
            $enrollment->completed_at = now();
        }

        $enrollment->save();

        return response()->json([
            'data' => [
                'id'           => $enrollment->id,
                'status'       => $enrollment->status,
                'progress_pct' => $enrollment->progress_pct,
                'completed_at' => $enrollment->completed_at?->toDateString(),
            ],
            'meta'    => [],
            'message' => 'success',
        ]);
    }
}
