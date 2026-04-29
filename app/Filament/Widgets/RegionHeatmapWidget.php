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
            // training breakdown per region
            $notStarted = 0; $inProgress = 0; $completed = 0; $overdue = 0;

            // simplified counts via joins
            $rows = DB::table('batch_participants')
                ->join('employees', 'batch_participants.employee_id', '=', 'employees.id')
                ->where('employees.region', $region)
                ->selectRaw("SUM(CASE WHEN batch_participants.status = 'menunggu_undangan' THEN 1 ELSE 0 END) as not_started, SUM(CASE WHEN batch_participants.status = 'hadir' THEN 1 ELSE 0 END) as in_progress, SUM(CASE WHEN batch_participants.status = 'lulus' THEN 1 ELSE 0 END) as completed, SUM(CASE WHEN batch_participants.status = 'batal' THEN 1 ELSE 0 END) as overdue")
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
                    'menunggu_undangan' => $notStarted,
                    'hadir' => $inProgress,
                    'lulus' => $completed,
                    'batal' => $overdue,
                ],
                'percentages' => [
                    'menunggu_undangan' => $total > 0 ? round(($notStarted / $total) * 100) : 0,
                    'hadir' => $total > 0 ? round(($inProgress / $total) * 100) : 0,
                    'lulus' => $total > 0 ? round(($completed / $total) * 100) : 0,
                    'batal' => $total > 0 ? round(($overdue / $total) * 100) : 0,
                ],
            ];
        }
    }
}


