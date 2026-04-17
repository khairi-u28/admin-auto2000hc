<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * GET /api/v1/employees
     * List employees with pagination and filters.
     */
    public function index(Request $request)
    {
        $query = Employee::with(['branch', 'jobRole'])
            ->when($request->filled('branch_id'), fn ($q) => $q->where('branch_id', $request->branch_id))
            ->when($request->filled('job_role_id'), fn ($q) => $q->where('job_role_id', $request->job_role_id))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('employee_type'), fn ($q) => $q->where('employee_type', $request->employee_type))
            ->when($request->filled('search'), fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('nrp', 'like', "%{$request->search}%")
                  ->orWhere('full_name', 'like', "%{$request->search}%");
            }));

        $perPage  = min((int) $request->input('per_page', 15), 100);
        $paginate = $query->paginate($perPage);

        return response()->json([
            'data' => $paginate->map(fn (Employee $e) => $this->formatEmployee($e)),
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
     * GET /api/v1/employees/{id}
     * Get a single employee with relations.
     */
    public function show(string $id)
    {
        $employee = Employee::with([
            'branch',
            'jobRole',
            'trainingRecords.competencyTrack',
            'developmentPrograms',
            'enrollments.curriculum',
        ])->findOrFail($id);

        return response()->json([
            'data'    => $this->formatEmployee($employee, detailed: true),
            'meta'    => [],
            'message' => 'success',
        ]);
    }

    /**
     * PUT /api/v1/employees/{id}
     * Update employee HAV data.
     */
    public function update(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'hav_score'    => ['nullable', 'integer', 'between:1,11'],
            'hav_category' => ['nullable', 'string', 'max:50'],
            'grade'        => ['nullable', 'string', 'max:10'],
            'status'       => ['nullable', 'in:active,inactive'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data'    => null,
                'meta'    => [],
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $employee->fill($validator->validated());
        $employee->save();

        return response()->json([
            'data'    => $this->formatEmployee($employee->refresh()),
            'meta'    => [],
            'message' => 'success',
        ]);
    }

    private function formatEmployee(Employee $e, bool $detailed = false): array
    {
        $base = [
            'id'            => $e->id,
            'nrp'           => $e->nrp,
            'full_name'     => $e->full_name,
            'position_name' => $e->position_name,
            'employee_type' => $e->employee_type,
            'status'        => $e->status,
            'hav_score'     => $e->hav_score,
            'hav_category'  => $e->hav_category,
            'grade'         => $e->grade,
            'entry_date'    => $e->entry_date?->toDateString(),
            'branch'        => $e->branch ? ['id' => $e->branch->id, 'name' => $e->branch->name, 'code' => $e->branch->code] : null,
            'job_role'      => $e->jobRole ? ['id' => $e->jobRole->id, 'name' => $e->jobRole->name, 'code' => $e->jobRole->code] : null,
        ];

        if ($detailed) {
            $base['training_records']    = $e->trainingRecords->map(fn ($r) => [
                'id'                   => $r->id,
                'competency_track'     => $r->competencyTrack?->name,
                'level_achieved'       => $r->level_achieved,
                'completion_date'      => $r->completion_date?->toDateString(),
                'certification_number' => $r->certification_number,
            ])->toArray();

            $base['development_programs'] = $e->developmentPrograms->map(fn ($p) => [
                'id'           => $p->id,
                'program_name' => $p->program_name,
                'status'       => $p->status,
                'period_year'  => $p->period_year,
                'pk_score'     => $p->pk_score,
            ])->toArray();

            $base['enrollments'] = $e->enrollments->map(fn ($en) => [
                'id'           => $en->id,
                'curriculum'   => $en->curriculum?->title,
                'status'       => $en->status,
                'progress_pct' => $en->progress_pct,
                'due_date'     => $en->due_date?->toDateString(),
            ])->toArray();
        }

        return $base;
    }
}
