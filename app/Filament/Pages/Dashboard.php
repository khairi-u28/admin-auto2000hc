<?php

namespace App\Filament\Pages;

use App\Models\Batch;
use App\Models\BatchParticipant;
use App\Models\Branch;
use App\Models\Competency;
use App\Models\Employee;
use App\Models\JobRole;
use App\Models\LearningPath;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Dashboard extends BaseDashboard
{
    protected string $view = 'filament.pages.dashboard';

    public function getViewData(): array
    {
        // ── ORG STRUCTURE ──────────────────────────────────────────
        $totalKaryawan  = Employee::where('status', 'active')->count();
        $totalKaryawanAll = Employee::count();
        $totalRegion    = Branch::distinct()->whereNotNull('region')
            ->pluck('region')->filter()->count();
        $totalArea      = Branch::distinct()->whereNotNull('area')
            ->pluck('area')->filter()->count();
        $totalCabang    = Branch::count();
        $cabangByTipe   = Branch::select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')->pluck('count', 'type')->toArray();

        // ── BATCH STATS ────────────────────────────────────────────
        $batchStatusCounts = [];
        $batchTrend        = [];
        $recentBatches     = collect();
        try {
            $batchStatusCounts = Batch::select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')->pluck('count', 'status')->toArray();

            $recentBatches = Batch::with(['competency', 'branch'])
                ->withCount(['participants as total_participants'])
                ->withCount(['participants as lulus_participants' => fn($q) => $q->where('status', 'lulus')])
                ->whereIn('status', ['open', 'berlangsung', 'selesai'])
                ->orderByDesc('updated_at')->limit(5)->get()
                ->map(function($batch) {
                    $batch->kelulusan_pct = $batch->total_participants > 0 
                        ? round($batch->lulus_participants / $batch->total_participants * 100) : 0;
                    return $batch;
                });
        } catch (\Exception $e) { /* tables not yet migrated */
        }

        // ── LEARNING PATH FULFILLMENT BY JOB ROLE ─────────────────
        $lpFulfillment = [];
        try {
            $roles = JobRole::withCount('employees as emp_count')
                ->having('emp_count', '>', 0)->get();

            foreach ($roles->take(10) as $role) {
                $empIds = Employee::where('job_role_id', $role->id)
                    ->where('status', 'active')->pluck('id');
                if ($empIds->isEmpty()) continue;

                $lulus = BatchParticipant::whereIn('employee_id', $empIds)
                    ->where('status', 'lulus')
                    ->distinct('employee_id')->count('employee_id');

                $lpFulfillment[] = [
                    'name'    => $role->name,
                    'total'   => $empIds->count(),
                    'lulus'   => $lulus,
                    'pct'     => $empIds->count() > 0
                        ? round($lulus / $empIds->count() * 100) : 0,
                ];
            }
            usort($lpFulfillment, fn($a, $b) => $a['pct'] - $b['pct']);
        } catch (\Exception $e) {
        }

        // ── TOP COMPETENCY GAPS ────────────────────────────────────
        $competencyGaps = [];
        try {
            $competencyGaps = Competency::select('competencies.id', 'competencies.name')
                ->selectRaw('COUNT(DISTINCT employees.id) as total_emp')
                ->selectRaw('COUNT(DISTINCT CASE WHEN batch_participants.status = "lulus" 
                    THEN batch_participants.employee_id END) as lulus_count')
                ->join(
                    'learning_path_competencies',
                    'competencies.id',
                    '=',
                    'learning_path_competencies.competency_id'
                )
                ->join(
                    'learning_paths',
                    'learning_path_competencies.learning_path_id',
                    '=',
                    'learning_paths.id'
                )
                ->join(
                    'employees',
                    'employees.job_role_id',
                    '=',
                    'learning_paths.job_role_id'
                )
                ->leftJoin('batch_participants', function ($join) {
                    $join->on('batch_participants.employee_id', '=', 'employees.id')
                        ->whereIn('batch_participants.status', ['lulus']);
                })
                ->leftJoin('batches', function ($join) {
                    $join->on('batches.id', '=', 'batch_participants.batch_id')
                        ->on('batches.competency_id', '=', 'competencies.id');
                })
                ->where('employees.status', 'active')
                ->where('learning_paths.status', 'published')
                ->groupBy('competencies.id', 'competencies.name')
                ->get()
                ->map(fn($c) => [
                    'name'     => $c->name,
                    'total'    => $c->total_emp,
                    'lulus'    => $c->lulus_count,
                    'gap'      => max(0, $c->total_emp - $c->lulus_count),
                    'gap_pct'  => $c->total_emp > 0
                        ? round(($c->total_emp - $c->lulus_count) / $c->total_emp * 100) : 0,
                ])
                ->sortByDesc('gap')->take(8)->values()->toArray();
        } catch (\Exception $e) {
        }

        // ── EARLY WARNINGS ─────────────────────────────────────────
        $warnings = [];
        try {
            $overdue = Batch::where('status', 'berlangsung')
                ->where('end_date', '<', now()->toDateString())->count();
            if ($overdue > 0)
                $warnings[] = [
                    'type' => 'danger',
                    'count' => $overdue,
                    'label' => "Batch Terlambat Diselesaikan",
                    'sub' => 'Status berlangsung melewati tanggal selesai'
                ];

            $inactive = Employee::where('status', 'active')
                ->whereDoesntHave('batchParticipations', fn($q) =>
                $q->where('updated_at', '>=', now()->subDays(90)))
                ->count();
            if ($inactive > 0)
                $warnings[] = [
                    'type' => 'warning',
                    'count' => $inactive,
                    'label' => "Karyawan Tanpa Aktivitas Learning",
                    'sub' => 'Tidak ada batch activity dalam 90 hari terakhir'
                ];

            $lowFulfill = collect($lpFulfillment)
                ->where('pct', '<', 30)->count();
            if ($lowFulfill > 0)
                $warnings[] = [
                    'type' => 'warning',
                    'count' => $lowFulfill,
                    'label' => "Jabatan Fulfillment < 30%",
                    'sub' => 'Learning path hampir tidak ada yang terpenuhi'
                ];
        } catch (\Exception $e) {
        }

        return compact(
            'totalKaryawan',
            'totalKaryawanAll',
            'totalRegion',
            'totalArea',
            'totalCabang',
            'cabangByTipe',
            'batchStatusCounts',
            'recentBatches',
            'lpFulfillment',
            'competencyGaps',
            'warnings'
        );
    }
}
