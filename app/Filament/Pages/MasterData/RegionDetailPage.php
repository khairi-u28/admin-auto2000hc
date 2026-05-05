<?php

namespace App\Filament\Pages\MasterData;

use App\Models\Batch;
use App\Models\BatchParticipant;
use App\Models\Branch;
use App\Models\Competency;
use App\Models\Employee;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class RegionDetailPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected string $view = 'filament.pages.master-data.region-detail';

    public string $region = '';

    public function mount(): void
    {
        $this->region = request('region', '');
        if (!$this->region) abort(404);
    }

    public function getViewData(): array
    {
        try {
            $branchIds = Branch::where('region', $this->region)->pluck('id');
            $batchIds  = Batch::whereIn('branch_id', $branchIds)->pluck('id');
            $empIds    = Employee::whereIn('branch_id', $branchIds)
                ->where('status','active')->pluck('id');

            // KPIs
            $totalKaryawan = $empIds->count();
            $activeBatch   = Batch::whereIn('branch_id',$branchIds)
                ->whereIn('status',['open','berlangsung'])->count();
            $totalLulus    = BatchParticipant::whereIn('batch_id',$batchIds)
                ->where('status','lulus')->count();
            $totalEval     = BatchParticipant::whereIn('batch_id',$batchIds)
                ->whereIn('status',['lulus','tidak_lulus'])->count();
            $kelulusanPct  = $totalEval>0
                ? round($totalLulus/$totalEval*100,1):0;
            $rbhName       = Employee::whereHas('jobRole',
                fn($q)=>$q->where('code','RBH01'))
                ->where('region',$this->region)->value('full_name') ?? '-';

            // Area breakdown
            $areaBreakdown = Branch::where('region',$this->region)
                ->select('area')
                ->selectRaw('COUNT(DISTINCT branches.id) as total_cabang')
                ->selectRaw('COUNT(DISTINCT employees.id) as total_karyawan')
                ->leftJoin('employees','employees.branch_id','=','branches.id')
                ->groupBy('area')->orderBy('area')->get()
                ->map(function($row) use ($batchIds) {
                    $areaBranchIds = Branch::where('area',$row->area)->pluck('id');
                    $areaBatchIds  = Batch::whereIn('branch_id',$areaBranchIds)->pluck('id');
                    $lulus = BatchParticipant::whereIn('batch_id',$areaBatchIds)
                        ->where('status','lulus')->count();
                    $eval  = BatchParticipant::whereIn('batch_id',$areaBatchIds)
                        ->whereIn('status',['lulus','tidak_lulus'])->count();
                    $abh   = Employee::whereHas('jobRole',
                        fn($q)=>$q->where('code','ABH01'))
                        ->where('area',$row->area)->value('full_name') ?? '-';
                    return [
                        'area'          => $row->area,
                        'abh'           => $abh,
                        'total_cabang'  => $row->total_cabang,
                        'total_karyawan'=> $row->total_karyawan,
                        'kelulusan_pct' => $eval>0?round($lulus/$eval*100,1):null,
                    ];
                });

            // Top gaps in this region
            $topGaps = Competency::select('competencies.id','competencies.name')
                ->selectRaw('COUNT(DISTINCT CASE WHEN batch_participants.status="lulus"
                    THEN batch_participants.employee_id END) as lulus_count')
                ->selectRaw('COUNT(DISTINCT employees.id) as total_emp')
                ->join('learning_path_competencies',
                    'competencies.id','=','learning_path_competencies.competency_id')
                ->join('learning_paths',
                    'learning_path_competencies.learning_path_id','=','learning_paths.id')
                ->join('employees',
                    'employees.job_role_id','=','learning_paths.job_role_id')
                ->leftJoin('batches',
                    'batches.competency_id','=','competencies.id')
                ->leftJoin('batch_participants', function($j) use ($empIds) {
                    $j->on('batch_participants.batch_id','=','batches.id')
                      ->whereIn('batch_participants.employee_id',$empIds)
                      ->where('batch_participants.status','lulus');
                })
                ->whereIn('employees.id',$empIds)
                ->where('employees.status','active')
                ->groupBy('competencies.id','competencies.name')
                ->havingRaw('COUNT(DISTINCT employees.id) > 0')
                ->orderByRaw('(COUNT(DISTINCT employees.id) - COUNT(DISTINCT CASE WHEN batch_participants.status = "lulus" THEN batch_participants.employee_id END)) DESC')
                ->limit(5)->get();

            // Recent batches
            $recentBatches = Batch::whereIn('branch_id',$branchIds)
                ->with('competency','branch')
                ->orderByDesc('start_date')->limit(5)->get();

        } catch (\Exception $e) {
            return ['region'=>$this->region,'totalKaryawan'=>0,
                'activeBatch'=>0,'kelulusanPct'=>0,'rbhName'=>'-',
                'areaBreakdown'=>collect(),'topGaps'=>collect(),
                'recentBatches'=>collect()];
        }

        return compact('totalKaryawan','activeBatch','totalLulus',
            'kelulusanPct','rbhName','areaBreakdown','topGaps','recentBatches');
    }
}
