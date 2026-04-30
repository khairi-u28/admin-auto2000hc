<?php
namespace App\Filament\Pages\MasterData;

use App\Models\Area;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class AreaPage extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationGroup(): ?string
    {
        return 'Master Data';
    }

    public static function getNavigationIcon(): Heroicon|string|null
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
        return Area::select('areas.nama_area as area', 'regions.nama_region as region', 'areas.nama_abh as nama_abh')
            ->selectRaw('COUNT(DISTINCT branches.id) as total_cabang')
            ->selectRaw('COUNT(DISTINCT employees.id) as total_karyawan')
            ->selectRaw('AVG(employees.hav_score) as avg_hav_score')
            ->selectRaw('COUNT(CASE WHEN employees.hav_score >= 8 THEN 1 END) as high_performers')
            ->leftJoin('branches', 'areas.id', '=', 'branches.area_id')
            ->leftJoin('employees', 'branches.id', '=', 'employees.branch_id')
            ->leftJoin('regions', 'areas.region_id', '=', 'regions.id')
            ->groupBy('areas.id', 'regions.nama_region', 'areas.nama_abh')
            ->orderBy('regions.nama_region')
            ->orderBy('areas.nama_area')
            ->get()
            ->toArray();
    }
}
