<?php

namespace App\Filament\Widgets;

use App\Models\Branch;
use App\Models\Employee;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RegionHeatmapWidget extends Widget
{
    protected static ?string $heading = 'Performa Region';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.widgets.region-heatmap-widget';

    public array $regions = [];

    public function mount(): void
    {
        // Hardcoded behavioral and AUBO values per spec
        $mapping = [
            'DKI,JABAR,PRIME FLEET' => ['behavior' => 4.25, 'aubo' => '61.1%'],
            'JATKALBAL' => ['behavior' => 4.29, 'aubo' => '62.4%'],
            'SUMATERA' => ['behavior' => 4.30, 'aubo' => '60.7%'],
        ];

        foreach ($mapping as $region => $meta) {
            $activeEmployees = Employee::where('status', 'active')->where('region', $region)->count();
            // enrollment breakdown per region
            $notStarted = 0; $inProgress = 0; $completed = 0; $overdue = 0;

            // simplified counts via joins
            $rows = DB::table('enrollments')
                ->join('employees', 'enrollments.employee_id', '=', 'employees.id')
                ->where('employees.region', $region)
                ->selectRaw("SUM(CASE WHEN enrollments.status = 'not_started' THEN 1 ELSE 0 END) as not_started, SUM(CASE WHEN enrollments.status = 'in_progress' THEN 1 ELSE 0 END) as in_progress, SUM(CASE WHEN enrollments.status = 'completed' THEN 1 ELSE 0 END) as completed, SUM(CASE WHEN enrollments.status = 'overdue' THEN 1 ELSE 0 END) as overdue")
                ->first();

            if ($rows) {
                $notStarted = (int) $rows->not_started;
                $inProgress = (int) $rows->in_progress;
                $completed = (int) $rows->completed;
                $overdue = (int) $rows->overdue;
            }

            $total = $notStarted + $inProgress + $completed + $overdue;

            $this->regions[] = [
                'name' => $region,
                'active_employees' => $activeEmployees,
                'behavior' => $meta['behavior'],
                'aubo' => $meta['aubo'],
                'breakdown' => [
                    'not_started' => $notStarted,
                    'in_progress' => $inProgress,
                    'completed' => $completed,
                    'overdue' => $overdue,
                ],
                'percentages' => [
                    'not_started' => $total > 0 ? round(($notStarted / $total) * 100) : 0,
                    'in_progress' => $total > 0 ? round(($inProgress / $total) * 100) : 0,
                    'completed' => $total > 0 ? round(($completed / $total) * 100) : 0,
                    'overdue' => $total > 0 ? round(($overdue / $total) * 100) : 0,
                ],
            ];
        }
    }
}
