<?php
namespace App\Filament\Pages\MasterData;

use App\Models\Batch;
use App\Models\BatchParticipant;
use App\Models\Branch;
use App\Models\Employee;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class AreaPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationGroup(): string | \UnitEnum | null
    {
        return 'Master Data';
    }

    public static function getNavigationIcon(): string | \BackedEnum | \Illuminate\Contracts\Support\Htmlable | null
    {
        return Heroicon::OutlinedMap;
    }

    public static function getNavigationLabel(): string
    {
        return 'Area';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public function getView(): string
    {
        return 'filament.pages.master-data.area';
    }

    public function getAreaData(): array
    {
        try {
            return Branch::select('area','region')
                ->selectRaw('COUNT(DISTINCT branches.id) as total_cabang')
                ->selectRaw('COUNT(DISTINCT employees.id) as total_karyawan')
                ->leftJoin('employees','employees.branch_id','=','branches.id')
                ->whereNotNull('area')
                ->groupBy('area','region')
                ->orderBy('region')->orderBy('area')
                ->get()
                ->map(function($row) {
                    $branchIds = Branch::where('area',$row->area)->pluck('id');
                    $batchIds  = Batch::whereIn('branch_id',$branchIds)->pluck('id');
                    $lulus     = BatchParticipant::whereIn('batch_id',$batchIds)
                        ->where('status','lulus')->count();
                    $eval      = BatchParticipant::whereIn('batch_id',$batchIds)
                        ->whereIn('status',['lulus','tidak_lulus'])->count();
                    $activeBatch = Batch::whereIn('branch_id',$branchIds)
                        ->whereIn('status',['open','berlangsung'])->count();
                    $abh = Employee::whereHas('jobRole',
                        fn($q)=>$q->where('code','ABH01'))
                        ->where('area',$row->area)->value('full_name') ?? '-';
                    return [
                        'area'           => $row->area,
                        'region'         => $row->region,
                        'abh_name'       => $abh,
                        'total_cabang'   => $row->total_cabang,
                        'total_karyawan' => $row->total_karyawan,
                        'active_batch'   => $activeBatch,
                        'kelulusan_pct'  => $eval>0
                            ? round($lulus/$eval*100,1) : null,
                    ];
                })->toArray();
        } catch (\Exception $e) { return []; }
    }
}
