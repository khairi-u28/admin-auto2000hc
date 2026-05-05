<?php
namespace App\Filament\Pages;

use App\Models\Batch;
use App\Models\BatchParticipant;
use App\Models\Branch;
use App\Models\Competency;
use App\Models\Employee;
use App\Models\JobRole;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class NasionalPage extends Page
{
    protected string $view = 'filament.pages.nasional';
    protected static string|null $navigationLabel = 'Nasional';
    protected static string|\UnitEnum|null $navigationGroup = 'Report';
    protected static ?int $navigationSort = 1;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-alt';

    public int $filterYear;
    public string $filterPeriode = 'full';
    public string $filterTipe = 'all';
    public string $filterRegion = 'all';

    public function mount(): void
    {
        // Try to get max year from batches, fallback to current year
        $maxYear = Batch::max(DB::raw('YEAR(end_date)'));

        // If max year is found, use it. Otherwise use current year.
        $this->filterYear = $maxYear ?? now()->year;

        // If there's no data for the current year but there is data in the past,
        // use the latest year that actually has batches.
        if (Batch::whereYear('end_date', $this->filterYear)->count() === 0) {
            $latestWithData = Batch::orderByDesc('end_date')->first();
            if ($latestWithData) {
                $this->filterYear = $latestWithData->end_date->year;
            }
        }
    }

    public function updatedFilterYear(): void
    {
        $this->dispatch('$refresh');
    }
    public function updatedFilterPeriode(): void
    {
        $this->dispatch('$refresh');
    }
    public function updatedFilterTipe(): void
    {
        $this->dispatch('$refresh');
    }
    public function updatedFilterRegion(): void
    {
        $this->dispatch('$refresh');
    }

    protected function buildBatchQuery()
    {
        $q = Batch::whereYear('end_date', $this->filterYear);
        if ($this->filterPeriode === 's1')
            $q->whereMonth('end_date', '<=', 6);
        if ($this->filterPeriode === 's2')
            $q->whereMonth('end_date', '>=', 7);
        if ($this->filterTipe !== 'all')
            $q->where('type', $this->filterTipe);
        if ($this->filterRegion !== 'all') {
            $branchIds = Branch::where('region', $this->filterRegion)->pluck('id');
            $q->whereIn('branch_id', $branchIds);
        }
        return $q;
    }

    public function getViewData(): array
    {
        try {
            $batchQ = $this->buildBatchQuery();
            $batchIds = (clone $batchQ)->pluck('id')->toArray();

            // Stats
            $totalSelesai = (clone $batchQ)->where('status', 'selesai')->count();
            $totalPeserta = BatchParticipant::whereIn('batch_id', $batchIds)
                ->distinct('employee_id')->count('employee_id');
            $totalLulus = BatchParticipant::whereIn('batch_id', $batchIds)
                ->where('status', 'lulus')->count();
            $totalTidakLulus = BatchParticipant::whereIn('batch_id', $batchIds)
                ->where('status', 'tidak_lulus')->count();
            $totalEval = $totalLulus + $totalTidakLulus;
            $kelulusanPct = $totalEval > 0
                ? round($totalLulus / $totalEval * 100, 1) : 0;

            // Avg feedback ratings
            $avgTraining = DB::table('batch_feedback')
                ->whereIn('batch_id', $batchIds)
                ->where('is_submitted', true)
                ->selectRaw('ROUND(AVG((training_relevance + training_material_quality + 
                    training_schedule + training_facility) / 4.0), 1) as avg')
                ->value('avg') ?? 0;
            $avgTrainer = DB::table('batch_feedback')
                ->whereIn('batch_id', $batchIds)
                ->where('is_submitted', true)
                ->selectRaw('ROUND(AVG((trainer_mastery + trainer_delivery + 
                    trainer_responsiveness + trainer_attitude) / 4.0), 1) as avg')
                ->value('avg') ?? 0;
            $totalOverdue = Batch::where('status', 'berlangsung')
                ->where('end_date', '<', now()->toDateString())->count();

            // Region performance
            $regionData = Branch::select('branches.region')
                ->selectRaw('COUNT(DISTINCT batches.id) as total_batch')
                ->selectRaw('COUNT(DISTINCT batch_participants.employee_id) as total_peserta')
                ->selectRaw('COUNT(DISTINCT CASE WHEN batch_participants.status = "lulus" 
                    THEN batch_participants.employee_id END) as total_lulus')
                ->selectRaw('ROUND(AVG((batch_feedback.training_relevance + 
                    batch_feedback.training_material_quality + 
                    batch_feedback.training_schedule + 
                    batch_feedback.training_facility) / 4.0), 1) as avg_rating')
                ->leftJoin('batches', 'batches.branch_id', '=', 'branches.id')
                ->leftJoin(
                    'batch_participants',
                    'batch_participants.batch_id',
                    '=',
                    'batches.id'
                )
                ->leftJoin('batch_feedback', function ($j) {
                    $j->on('batch_feedback.batch_id', '=', 'batches.id')
                        ->where('batch_feedback.is_submitted', true);
                })
                ->whereIn('batches.id', $batchIds)
                ->groupBy('branches.region')
                ->orderBy('branches.region')
                ->get();

            // Trainer Table Data
            $trainerData = DB::table('users')
                ->leftJoin('employees', 'employees.user_id', '=', 'users.id')
                ->leftJoin('branches', 'employees.branch_id', '=', 'branches.id')
                ->join('batches', 'batches.pic_id', '=', 'users.id')
                ->whereIn('batches.id', $batchIds)
                ->select(
                    'users.id as user_id',
                    DB::raw('COALESCE(employees.nama_lengkap, users.name) as nama_lengkap'),
                    DB::raw('COALESCE(employees.nrp, "-") as nrp'),
                    DB::raw('COALESCE(branches.kode_cabang, "-") as kode_cabang'),
                    DB::raw('COALESCE(branches.name, "HO") as branch_name')
                )
                ->selectRaw('COUNT(batches.id) as total_training')
                ->groupBy('users.id', 'employees.nama_lengkap', 'users.name', 'employees.nrp', 'branches.kode_cabang', 'branches.name')
                ->orderByRaw('COUNT(batches.id) DESC')
                ->limit(10)
                ->get();

            // Monthly trend (12 months of selected year)
            $monthlyTrend = collect(range(1, 12))->map(function ($m) {
                $selesai = Batch::where('status', 'selesai')
                    ->whereYear('end_date', $this->filterYear)
                    ->whereMonth('end_date', $m)->count();
                $lulus = BatchParticipant::where('status', 'lulus')
                    ->whereHas('batch', fn($q) =>
                        $q->whereYear('end_date', $this->filterYear)
                            ->whereMonth('end_date', $m))
                    ->count();
                return ['month' => $m, 'selesai' => $selesai, 'lulus' => $lulus];
            });

            // Batch status distribution (filtered)
            $batchStatusCounts = (clone $batchQ)
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')->pluck('count', 'status')->toArray();

            // Supply vs gap per competency
            $competencyAnalysis = Competency::select('competencies.id', 'competencies.name')
                ->selectRaw('COUNT(DISTINCT CASE WHEN batch_participants.status = "lulus" 
                    THEN batch_participants.employee_id END) as supply')
                ->selectRaw('COUNT(DISTINCT employees.id) as demand')
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
                ->leftJoin('batches', function ($j) use ($batchIds) {
                    $j->on('batches.competency_id', '=', 'competencies.id')
                        ->whereIn('batches.id', $batchIds);
                })
                ->leftJoin('batch_participants', function ($j) {
                    $j->on('batch_participants.batch_id', '=', 'batches.id')
                        ->where('batch_participants.status', 'lulus');
                })
                ->where('employees.status', 'active')
                ->groupBy('competencies.id', 'competencies.name')
                ->havingRaw('COUNT(DISTINCT employees.id) > 0')
                ->orderByRaw('(COUNT(DISTINCT employees.id) - COUNT(DISTINCT CASE WHEN batch_participants.status = "lulus" THEN batch_participants.employee_id END)) DESC')
                ->limit(8)->get()->toArray();

            // Job Role x Competency heatmap
            $topRoles = JobRole::withCount([
                'employees as emp_count' =>
                    fn($q) => $q->where('status', 'active')
            ])
                ->having('emp_count', '>', 0)
                ->orderByDesc('emp_count')->limit(6)->get();
            $topComps = Competency::whereIn(
                'id',
                BatchParticipant::whereIn('batch_id', $batchIds)
                    ->join('batches', 'batches.id', '=', 'batch_participants.batch_id')
                    ->distinct()->pluck('batches.competency_id')
            )
                ->limit(6)->get();

            $heatmapData = [];
            foreach ($topRoles as $role) {
                $empIds = Employee::where('job_role_id', $role->id)
                    ->where('status', 'active')->pluck('id');
                $row = ['role' => $role->name, 'cells' => []];
                foreach ($topComps as $comp) {
                    $lulus = BatchParticipant::whereIn('employee_id', $empIds)
                        ->where('status', 'lulus')
                        ->whereHas(
                            'batch',
                            fn($q) => $q->where('competency_id', $comp->id)
                        )
                        ->distinct('employee_id')->count('employee_id');
                    $pct = $empIds->count() > 0
                        ? round($lulus / $empIds->count() * 100) : 0;
                    $row['cells'][] = ['comp' => $comp->name, 'pct' => $pct];
                }
                $heatmapData[] = $row;
            }

            // Overdue batches detail
            $overdueBatches = Batch::where('status', 'berlangsung')
                ->where('end_date', '<', now()->toDateString())
                ->with('competency', 'branch')
                ->orderBy('end_date')->limit(5)->get();

            // Lowest fulfillment job roles
            $lowFulfillRoles = [];
            foreach (JobRole::withCount([
                'employees as ec' =>
                    fn($q) => $q->where('status', 'active')
            ])
                ->having('ec', '>', 0)->get() as $role) {
                $empIds = Employee::where('job_role_id', $role->id)
                    ->pluck('id');
                $lulus = BatchParticipant::whereIn('employee_id', $empIds)
                    ->where('status', 'lulus')
                    ->distinct('employee_id')->count('employee_id');
                $pct = $empIds->count() > 0
                    ? round($lulus / $empIds->count() * 100) : 0;
                $lowFulfillRoles[] = [
                    'name' => $role->name,
                    'pct' => $pct,
                    'total' => $empIds->count()
                ];
            }
            usort($lowFulfillRoles, fn($a, $b) => $a['pct'] - $b['pct']);
            $lowFulfillRoles = array_slice($lowFulfillRoles, 0, 6);

            $regions = Branch::distinct()->whereNotNull('region')
                ->pluck('region')->filter()->sort()->values();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("NasionalPage Error: " . $e->getMessage());
            return $this->emptyNasionalData();
        }

        return compact(
            'totalSelesai',
            'totalPeserta',
            'totalLulus',
            'totalTidakLulus',
            'totalEval',
            'kelulusanPct',
            'avgTraining',
            'avgTrainer',
            'totalOverdue',
            'regionData',
            'monthlyTrend',
            'batchStatusCounts',
            'competencyAnalysis',
            'heatmapData',
            'overdueBatches',
            'lowFulfillRoles',
            'regions',
            'trainerData'
        );
    }

    private function emptyNasionalData(): array
    {
        return [
            'totalSelesai' => 0,
            'totalPeserta' => 0,
            'totalLulus' => 0,
            'totalTidakLulus' => 0,
            'totalEval' => 0,
            'kelulusanPct' => 0,
            'avgTraining' => 0,
            'avgTrainer' => 0,
            'totalOverdue' => 0,
            'regionData' => collect(),
            'monthlyTrend' => collect(),
            'batchStatusCounts' => [],
            'competencyAnalysis' => [],
            'heatmapData' => [],
            'overdueBatches' => collect(),
            'lowFulfillRoles' => [],
            'regions' => collect(),
            'trainerData' => collect(),
        ];
    }
}