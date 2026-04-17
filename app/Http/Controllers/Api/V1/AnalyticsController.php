<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\TrainingRecord;
use App\Models\RoleCompetencyRequirement;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * GET /api/v1/analytics/overview
     */
    public function overview()
    {
        $totalEmployees      = Employee::where('status', 'active')->count();
        $activeEnrollments   = Enrollment::where('status', 'active')->count();
        $completedEnrollments = Enrollment::where('status', 'completed')->count();
        $totalEnrollments    = Enrollment::whereIn('status', ['active', 'completed'])->count();
        $completionRate      = $totalEnrollments > 0
            ? round(($completedEnrollments / $totalEnrollments) * 100, 1)
            : 0;
        $trainingRecords     = TrainingRecord::count();

        return response()->json([
            'data' => [
                'total_active_employees'    => $totalEmployees,
                'active_enrollments'        => $activeEnrollments,
                'completed_enrollments'     => $completedEnrollments,
                'completion_rate_pct'       => $completionRate,
                'total_training_records'    => $trainingRecords,
            ],
            'meta'    => [],
            'message' => 'success',
        ]);
    }

    /**
     * GET /api/v1/analytics/completion-by-department
     */
    public function completionByDepartment()
    {
        $rows = DB::table('enrollments')
            ->join('curricula', 'enrollments.curriculum_id', '=', 'curricula.id')
            ->select(
                'curricula.department',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN enrollments.status = 'completed' THEN 1 ELSE 0 END) as completed")
            )
            ->whereIn('enrollments.status', ['active', 'completed'])
            ->whereNotNull('curricula.department')
            ->groupBy('curricula.department')
            ->orderByDesc('total')
            ->get();

        $data = $rows->map(fn ($row) => [
            'department'      => $row->department,
            'total'           => (int) $row->total,
            'completed'       => (int) $row->completed,
            'completion_rate' => $row->total > 0
                ? round(($row->completed / $row->total) * 100, 1)
                : 0,
        ]);

        return response()->json([
            'data'    => $data,
            'meta'    => ['total_departments' => $data->count()],
            'message' => 'success',
        ]);
    }

    /**
     * GET /api/v1/analytics/competency-gap
     * Returns, per job role + competency, how many employees are below required level.
     */
    public function competencyGap()
    {
        $rows = DB::table('role_competency_requirements as req')
            ->join('job_roles', 'req.job_role_id', '=', 'job_roles.id')
            ->join('competencies', 'req.competency_id', '=', 'competencies.id')
            ->leftJoin('employees', 'employees.job_role_id', '=', 'job_roles.id')
            ->leftJoin('training_records', function ($join) {
                $join->on('training_records.employee_id', '=', 'employees.id')
                     ->on('training_records.competency_id', '=', 'req.competency_id');
            })
            ->select(
                'job_roles.id as job_role_id',
                'job_roles.name as job_role',
                'competencies.id as competency_id',
                'competencies.name as competency',
                'competencies.code as competency_code',
                'req.required_level',
                DB::raw('COUNT(DISTINCT employees.id) as total_employees'),
                DB::raw('SUM(CASE WHEN training_records.level_achieved >= req.required_level THEN 1 ELSE 0 END) as employees_met'),
                DB::raw('SUM(CASE WHEN training_records.level_achieved IS NULL OR training_records.level_achieved < req.required_level THEN 1 ELSE 0 END) as employees_gap')
            )
            ->where('employees.status', 'active')
            ->groupBy('req.job_role_id', 'req.competency_id', 'job_roles.id', 'job_roles.name', 'competencies.id', 'competencies.name', 'competencies.code', 'req.required_level')
            ->orderBy('employees_gap', 'desc')
            ->get();

        return response()->json([
            'data'    => $rows,
            'meta'    => ['total_rows' => $rows->count()],
            'message' => 'success',
        ]);
    }
}
