<?php

namespace App\Providers\Filament;

use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->sidebarCollapsibleOnDesktop()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#1A3A5C'),
                'danger'  => Color::hex('#C8102E'),
                'success' => Color::hex('#1B7A4E'),
                'warning' => Color::hex('#D97706'),
                'info'    => Color::hex('#0D7377'),
            ])
            ->brandName('Ruang Kompetensi')
            ->navigationGroups([
                NavigationGroup::make('Master Data')->icon('heroicon-o-circle-stack'),
                NavigationGroup::make('Knowledge Management')->icon('heroicon-o-book-open'),
                NavigationGroup::make('Training Management')->icon('heroicon-o-academic-cap'),
                NavigationGroup::make('Report')->icon('heroicon-o-presentation-chart-bar'),
                NavigationGroup::make('Sistem')->icon('heroicon-o-cog-6-tooth'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            // ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            // ->widgets([
            //     \App\Filament\Widgets\StatsOverviewWidget::class,
            //     \App\Filament\Widgets\DepartmentCompletionChartWidget::class,
            //     \App\Filament\Widgets\CompetencyDistributionWidget::class,
            //     \App\Filament\Widgets\RegionHeatmapWidget::class,
            //     // \App\Filament\Widgets\CompetencyGapWidget::class,
            //     \App\Filament\Widgets\BranchLeaderboardWidget::class,
            // ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
