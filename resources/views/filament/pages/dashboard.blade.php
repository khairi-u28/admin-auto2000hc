@extends('filament::page')

@section('content')
<div class="space-y-6">
  {{-- Using class constants ensures Livewire 3 can resolve the components correctly --}}
  <div>
    @livewire(\App\Filament\Widgets\StatsOverviewWidget::class)
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>@livewire(\App\Filament\Widgets\DepartmentCompletionChartWidget::class)</div>
    <div>@livewire(\App\Filament\Widgets\RegionCompletionChartWidget::class)</div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>@livewire(\App\Filament\Widgets\CompetencyDistributionWidget::class)</div>
  </div>
  <div>
    @livewire(\App\Filament\Widgets\RegionHeatmapWidget::class)
  </div>
  <div>
    @livewire(\App\Filament\Widgets\CompetencyGapWidget::class)
  </div>
  <div>
    @livewire(\App\Filament\Widgets\BranchLeaderboardWidget::class)
  </div>
</div>
@endsection
