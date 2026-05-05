<?php

namespace App\Filament\Resources\Branches\Pages;

use App\Filament\Resources\Branches\BranchResource;
use App\Models\Batch;
use App\Models\BatchParticipant;
use App\Models\Competency;
use App\Models\Employee;
use App\Models\JobRole;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBranch extends ViewRecord
{
    protected static string $resource = BranchResource::class;
    protected string $view = 'filament.resources.branches.view';

    public function getViewData(): array
    {
        $branch = $this->record;
        $batchIds = Batch::where('branch_id', $branch->id)->pluck('id');
        $empIds = Employee::where('branch_id', $branch->id)
            ->where('status', 'active')->pluck('id');

        try {
            // KPIs
            $totalKaryawan = $empIds->count();
            $totalAktif = $totalKaryawan;
            $activeBatch = Batch::where('branch_id', $branch->id)
                ->whereIn('status', ['open', 'berlangsung'])->count();
            $totalLulus = BatchParticipant::whereIn('batch_id', $batchIds)
                ->where('status', 'lulus')->count();
            $totalEval = BatchParticipant::whereIn('batch_id', $batchIds)
                ->whereIn('status', ['lulus', 'tidak_lulus'])->count();
            $kelulusanPct = $totalEval > 0 ? round($totalLulus / $totalEval * 100, 1) : 0;

            // Karyawan per jabatan
            $karyawanPerJabatan = JobRole::select('job_roles.name')
                ->selectRaw('COUNT(employees.id) as total')
                ->join('employees', 'employees.job_role_id', '=', 'job_roles.id')
                ->where('employees.branch_id', $branch->id)
                ->where('employees.status', 'active')
                ->groupBy('job_roles.id', 'job_roles.name')
                ->orderByDesc('total')->get();

            // LP fulfillment per jabatan at this branch
            $lpFulfillment = $karyawanPerJabatan->map(function ($jab) use ($branch, $batchIds) {
                $roleEmpIds = Employee::where('branch_id', $branch->id)
                    ->whereHas('jobRole', fn($q) => $q->where('name', $jab->name))
                    ->pluck('id');
                $lulus = BatchParticipant::whereIn('employee_id', $roleEmpIds)
                    ->whereIn('batch_id', $batchIds)
                    ->where('status', 'lulus')
                    ->distinct('employee_id')->count('employee_id');
                return [
                    'jabatan' => $jab->name,
                    'total' => $jab->total,
                    'lulus' => $lulus,
                    'pct' => $jab->total > 0
                        ? round($lulus / $jab->total * 100) : 0,
                ];
            });

            // Top gaps
            $topGaps = Competency::select('competencies.id', 'competencies.name')
                ->selectRaw('COUNT(DISTINCT CASE WHEN batch_participants.status="lulus"
                    THEN batch_participants.employee_id END) as lulus_count')
                ->selectRaw('COUNT(DISTINCT employees.id) as total_emp')
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
                ->leftJoin('batches', 'batches.competency_id', '=', 'competencies.id')
                ->leftJoin('batch_participants', function ($j) use ($empIds) {
                    $j->on('batch_participants.batch_id', '=', 'batches.id')
                        ->whereIn('batch_participants.employee_id', $empIds)
                        ->where('batch_participants.status', 'lulus');
                })
                ->whereIn('employees.id', $empIds)
                ->groupBy('competencies.id', 'competencies.name')
                ->havingRaw('COUNT(DISTINCT employees.id) > 0')
                ->orderByRaw('(COUNT(DISTINCT employees.id) - COUNT(DISTINCT CASE WHEN batch_participants.status = "lulus" THEN batch_participants.employee_id END)) DESC')
                ->limit(5)->get();

            // Batch history
            $batchHistory = Batch::where('branch_id', $branch->id)
                ->with('competency')
                ->withCount([
                    'participants as lulus_count' =>
                        fn($q) => $q->where('status', 'lulus'),
                    'participants as total_count',
                ])
                ->orderByDesc('start_date')->limit(8)->get();

            // Early warning
            $inactiveCount = Employee::where('branch_id', $branch->id)
                ->where('status', 'active')
                ->whereDoesntHave('batchParticipations', fn($q) =>
                    $q->where('updated_at', '>=', now()->subDays(90)))
                ->count();
            $upcomingEnd = Batch::where('branch_id', $branch->id)
                ->where('status', 'berlangsung')
                ->where('end_date', '<=', now()->addDays(7)->toDateString())
                ->where('end_date', '>=', now()->toDateString())
                ->get();

        } catch (\Exception $e) {
            return [
                'totalKaryawan' => 0,
                'activeBatch' => 0,
                'kelulusanPct' => 0,
                'karyawanPerJabatan' => collect(),
                'lpFulfillment' => collect(),
                'topGaps' => collect(),
                'batchHistory' => collect(),
                'inactiveCount' => 0,
                'upcomingEnd' => collect()
            ];
        }

        return compact(
            'totalKaryawan',
            'activeBatch',
            'kelulusanPct',
            'karyawanPerJabatan',
            'lpFulfillment',
            'topGaps',
            'batchHistory',
            'inactiveCount',
            'upcomingEnd'
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
