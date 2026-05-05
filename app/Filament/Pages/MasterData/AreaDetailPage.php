<?php

namespace App\Filament\Pages\MasterData;

use App\Models\Batch;
use App\Models\BatchParticipant;
use App\Models\Branch;
use App\Models\Competency;
use App\Models\Employee;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class AreaDetailPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected string $view = 'filament.pages.master-data.area-detail';

    public string $area = '';

    public function mount(): void
    {
        $this->area = request('area', '');
        if (!$this->area) abort(404);
    }

    public function getViewData(): array
    {
        try {
            $branches  = Branch::where('area', $this->area)
                ->withCount('employees')->get();
            $branchIds = $branches->pluck('id');
            $batchIds  = Batch::whereIn('branch_id',$branchIds)->pluck('id');
            $empIds    = Employee::whereIn('branch_id',$branchIds)
                ->where('status','active')->pluck('id');

            $totalKaryawan = $empIds->count();
            $totalCabang   = $branches->count();
            $activeBatch   = Batch::whereIn('branch_id',$branchIds)
                ->whereIn('status',['open','berlangsung'])->count();
            $totalLulus    = BatchParticipant::whereIn('batch_id',$batchIds)
                ->where('status','lulus')->count();
            $totalEval     = BatchParticipant::whereIn('batch_id',$batchIds)
                ->whereIn('status',['lulus','tidak_lulus'])->count();
            $kelulusanPct  = $totalEval>0
                ? round($totalLulus/$totalEval*100,1):0;
            $abhName       = Employee::whereHas('jobRole',
                fn($q)=>$q->where('code','ABH01'))
                ->where('area',$this->area)->value('full_name') ?? '-';
            $region        = Branch::where('area',$this->area)->value('region') ?? '-';

            // Cabang breakdown with stats
            $cabangBreakdown = $branches->map(function($b) {
                $bBatchIds = Batch::where('branch_id',$b->id)->pluck('id');
                $lulus     = BatchParticipant::whereIn('batch_id',$bBatchIds)
                    ->where('status','lulus')->count();
                $eval      = BatchParticipant::whereIn('batch_id',$bBatchIds)
                    ->whereIn('status',['lulus','tidak_lulus'])->count();
                return [
                    'id'             => $b->id,
                    'code'           => $b->kode_cabang,
                    'name'           => $b->nama,
                    'type'           => $b->type,
                    'employees_count'=> $b->employees_count,
                    'active_batch'   => Batch::where('branch_id',$b->id)
                        ->whereIn('status',['open','berlangsung'])->count(),
                    'kelulusan_pct'  => $eval>0?round($lulus/$eval*100,1):null,
                ];
            });

            // Top gaps
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
                ->leftJoin('batches','batches.competency_id','=','competencies.id')
                ->leftJoin('batch_participants', function($j) use ($empIds) {
                    $j->on('batch_participants.batch_id','=','batches.id')
                      ->whereIn('batch_participants.employee_id',$empIds)
                      ->where('batch_participants.status','lulus');
                })
                ->whereIn('employees.id',$empIds)
                ->groupBy('competencies.id','competencies.name')
                ->havingRaw('COUNT(DISTINCT employees.id) > 0')
                ->orderByRaw('(COUNT(DISTINCT employees.id) - COUNT(DISTINCT CASE WHEN batch_participants.status = "lulus" THEN batch_participants.employee_id END)) DESC')
                ->limit(5)->get();

            $recentBatches = Batch::whereIn('branch_id',$branchIds)
                ->with('competency','branch')
                ->orderByDesc('start_date')->limit(5)->get();

        } catch (\Exception $e) {
            return ['area'=>$this->area,'totalKaryawan'=>0,'totalCabang'=>0,
                'activeBatch'=>0,'kelulusanPct'=>0,'abhName'=>'-','region'=>'-',
                'cabangBreakdown'=>collect(),'topGaps'=>collect(),
                'recentBatches'=>collect()];
        }

        return compact('totalKaryawan','totalCabang','activeBatch',
            'kelulusanPct','abhName','region','cabangBreakdown','topGaps',
            'recentBatches');
    }
}
