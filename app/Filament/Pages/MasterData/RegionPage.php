<?php
namespace App\Filament\Pages\MasterData;

use App\Models\Batch;
use App\Models\BatchParticipant;
use App\Models\Branch;
use App\Models\Employee;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class RegionPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationGroup(): string | \UnitEnum | null
    {
        return 'Master Data';
    }

    public static function getNavigationIcon(): string | \BackedEnum | \Illuminate\Contracts\Support\Htmlable | null
    {
        return Heroicon::OutlinedGlobeAlt;
    }

    public static function getNavigationLabel(): string
    {
        return 'Region';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public function getView(): string
    {
        return 'filament.pages.master-data.region';
    }

    public function getRegionData(): array
    {
        try {
            $regions = Branch::select('region')
                ->selectRaw('COUNT(DISTINCT branches.id) as total_cabang')
                ->selectRaw('COUNT(DISTINCT area) as total_area')
                ->selectRaw('COUNT(DISTINCT employees.id) as total_karyawan')
                ->leftJoin('employees','employees.branch_id','=','branches.id')
                ->whereNotNull('region')
                ->groupBy('region')
                ->orderBy('region')
                ->get();

            return $regions->map(function($row) {
                $branchIds = Branch::where('region', $row->region)->pluck('id');
                $batchIds  = Batch::whereIn('branch_id', $branchIds)->pluck('id');
                $lulus     = BatchParticipant::whereIn('batch_id', $batchIds)
                    ->where('status','lulus')->count();
                $totalEval = BatchParticipant::whereIn('batch_id', $batchIds)
                    ->whereIn('status',['lulus','tidak_lulus'])->count();
                $activeBatch = Batch::whereIn('branch_id', $branchIds)
                    ->whereIn('status',['open','berlangsung'])->count();
                $rbh = Employee::whereHas('jobRole',
                    fn($q) => $q->where('code','RBH01'))
                    ->where('region', $row->region)->value('full_name');
                return [
                    'region'         => $row->region,
                    'total_area'     => $row->total_area,
                    'total_cabang'   => $row->total_cabang,
                    'total_karyawan' => $row->total_karyawan,
                    'active_batch'   => $activeBatch,
                    'kelulusan_pct'  => $totalEval > 0
                        ? round($lulus / $totalEval * 100, 1) : null,
                    'rbh_name'       => $rbh ?? '-',
                ];
            })->toArray();
        } catch (\Exception $e) { return []; }
    }
}
