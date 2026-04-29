<?php

namespace App\Filament\Widgets;

use App\Models\BatchParticipant;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RegionCompletionChartWidget extends ChartWidget
{
    protected ?string $heading = 'Tingkat Penyelesaian training per Region';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $rows = BatchParticipant::query()
            ->join('employees', 'batch_participants.employee_id', '=', 'employees.id')
            ->select(
                'employees.region',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN batch_participants.status = 'lulus' THEN 1 ELSE 0 END) as completed")
            )
            ->whereNotNull('employees.region')
            ->groupBy('employees.region')
            ->orderBy('employees.region')
            ->get();

        $labels = $rows->pluck('region')->toArray();
        $totalData = $rows->pluck('total')->toArray();
        $completedData = $rows->pluck('lulus')->toArray();

        $completionPct = $rows->map(function ($row) {
            return $row->total > 0 ? round(($row->completed / $row->total) * 100, 1) : 0;
        })->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label'           => 'Total training',
                    'type'            => 'bar',
                    'data'            => $totalData,
                    'backgroundColor' => 'rgba(26, 58, 92, 0.6)',
                    'borderColor'     => 'rgba(26, 58, 92, 1)',
                    'borderWidth'     => 1,
                ],
                [
                    'label'           => 'Selesai',
                    'type'            => 'bar',
                    'data'            => $completedData,
                    'backgroundColor' => 'rgba(27, 122, 78, 0.8)',
                    'borderColor'     => 'rgba(27, 122, 78, 1)',
                    'borderWidth'     => 1,
                ],
                [
                    'label'           => 'Completion %',
                    'type'            => 'line',
                    'data'            => $completionPct,
                    'borderColor'     => 'rgba(249, 115, 22, 1)',
                    'backgroundColor' => 'rgba(249, 115, 22, 0.1)',
                    'borderWidth'     => 2,
                    'tension'         => 0.4,
                    'yAxisID'         => 'y1',
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text'    => 'Jumlah training',
                    ],
                ],
                'y1' => [
                    'type'      => 'linear',
                    'position'  => 'right',
                    'beginAtZero' => true,
                    'max'       => 100,
                    'title' => [
                        'display' => true,
                        'text'    => 'Completion %',
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
        ];
    }
}

