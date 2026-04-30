<?php
namespace App\Filament\Pages\MasterData;

use App\Models\Region;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class RegionPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationGroup(): ?string
    {
        return 'Master Data';
    }

    public static function getNavigationIcon(): Heroicon|string|null
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
        return Region::select('regions.nama_region as region')
            ->selectRaw('COALESCE(regions.nama_rbh, MAX(CASE WHEN job_roles.code = "RBH01" THEN employees.full_name END)) as nama_rbh')
            ->selectRaw('COUNT(DISTINCT areas.id) as total_area')
            ->selectRaw('COUNT(DISTINCT branches.id) as total_cabang')
            ->selectRaw('COUNT(DISTINCT employees.id) as total_karyawan')
            ->selectRaw('AVG(employees.hav_score) as avg_hav_score')
            ->selectRaw('COUNT(CASE WHEN employees.hav_score >= 8 THEN 1 END) as high_performers')
            ->leftJoin('areas', 'regions.id', '=', 'areas.region_id')
            ->leftJoin('branches', 'areas.id', '=', 'branches.area_id')
            ->leftJoin('employees', 'branches.id', '=', 'employees.branch_id')
            ->leftJoin('job_roles', 'job_roles.id', '=', 'employees.job_role_id')
            ->groupBy('regions.id', 'regions.nama_region', 'regions.nama_rbh')
            ->orderBy('regions.nama_region')
            ->get()
            ->toArray();
    }
}
