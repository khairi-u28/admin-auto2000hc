<?php
namespace App\Filament\Pages\MasterData;

use App\Models\Branch;
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
        return Branch::select('region')
            ->selectRaw('COUNT(*) as total_cabang')
            ->selectRaw('COUNT(DISTINCT area) as total_area')
            ->groupBy('region')
            ->orderBy('region')
            ->get()
            ->toArray();
    }
}
