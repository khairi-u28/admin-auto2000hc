<?php
namespace App\Filament\Pages\MasterData;

use App\Models\Branch;
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
        return Branch::select('area', 'region')
            ->selectRaw('COUNT(*) as total_cabang')
            ->groupBy('area', 'region')
            ->orderBy('region')
            ->orderBy('area')
            ->get()
            ->toArray();
    }
}
