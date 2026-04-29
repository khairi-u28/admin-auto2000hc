<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\BatchParticipant;
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
        $totalEmployees      = Employee::where(['sedang_berjalan', 'selesai', 'lulus'])->count();
        $activebatch_participants   = BatchParticipant::where(['sedang_berjalan', 'selesai', 'lulus'])->count();
        $completedbatch_participants = BatchParticipant::where('status', 'lulus')->count();
        $totalbatch_participants    = BatchParticipant::whereIn('status', ['sedang_berjalan', 'selesai', 'lulus'])->count();
        $completionRate      = $totalbatch_participants > 0
            ? round(($completedbatch_participants / $totalbatch_participants) * 100, 1)
            : 0;
        $trainingRecords     = TrainingRecord::count();

        return response()->json([
            'data' => [
                'total_active_employees'    => $totalEmployees,
                'active_batch_participants'        => $activebatch_participants,
                'completed_batch_participants'     => $completedbatch_participants,
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
        $rows = DB::table('batch_participants')
            ->join('employees', 'batch_participants.employee_id', '=', 'employees.id')->join('job_roles', 'employees.job_role_id', '=', 'job_roles.id')
            ->select(
                'job_roles.department',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN batch_participants.status = 'completed' THEN 1 ELSE 0 END) as completed")
            )
            ->whereIn('batch_participants.status', ['active', 'completed'])
            ->whereNotNull('job_roles.department')
            ->groupBy('job_roles.department')
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
            ->join('competencies as ct', 'req.competency_id', '=', 'ct.id')
            ->leftJoin('employees', 'employees.job_role_id', '=', 'job_roles.id')
            ->leftJoin('training_records as tr', function ($join) {
                $join->on('tr.employee_id', '=', 'employees.id')
                     ->on('tr.competency_id', '=', 'req.competency_id');
            })
            ->select(
                'job_roles.id as job_role_id',
                'job_roles.name as job_role',
                'ct.id as competency_id',
                'ct.name as competency',
                'ct.code as competency_code',
                'req.minimum_level',
                DB::raw('COUNT(DISTINCT employees.id) as total_employees'),
                DB::raw('SUM(CASE WHEN tr.level_achieved >= req.minimum_level THEN 1 ELSE 0 END) as employees_met'),
                DB::raw('SUM(CASE WHEN tr.level_achieved IS NULL OR tr.level_achieved < req.minimum_level THEN 1 ELSE 0 END) as employees_gap')
            )
            ->whereNotNull('employees.id')
            ->where('employees.status', 'active')
            ->groupBy('job_roles.id', 'job_roles.name', 'ct.id', 'ct.name', 'ct.code', 'req.minimum_level')
            ->orderBy('employees_gap', 'desc')
            ->get();

        return response()->json([
            'data'    => $rows,
            'meta'    => ['total_rows' => $rows->count()],
            'message' => 'success',
        ]);
    }
}

