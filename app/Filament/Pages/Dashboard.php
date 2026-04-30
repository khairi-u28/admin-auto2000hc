<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // The native Filament dashboard view is used automatically.
    // Widgets are registered here (those not globally registered in AdminPanelProvider).

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverviewWidget::class,
            \App\Filament\Widgets\DepartmentCompletionChartWidget::class,
            \App\Filament\Widgets\CompetencyDistributionWidget::class,
            \App\Filament\Widgets\RegionHeatmapWidget::class,
            // \App\Filament\Widgets\CompetencyGapWidget::class,
            \App\Filament\Widgets\BranchLeaderboardWidget::class,
        ];
    }
}

