<?php

namespace App\Filament\Widgets;

use App\Models\Enrollment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DepartmentCompletionChartWidget extends ChartWidget
{
    protected ?string $heading = 'Penyelesaian Enrollment per Departemen';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $departments = ['Sales', 'Aftersales', 'PD', 'HC', 'GS'];

        $rows = Enrollment::query()
            ->join('curricula', 'enrollments.curriculum_id', '=', 'curricula.id')
            ->select(
                'curricula.department',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN enrollments.status = 'completed' THEN 1 ELSE 0 END) as completed")
            )
            ->whereIn('curricula.department', $departments)
            ->groupBy('curricula.department')
            ->get()
            ->keyBy('department');

        $totalData = [];
        $completedData = [];
        $completionPct = [];

        foreach ($departments as $dept) {
            $total = $rows->has($dept) ? (int) $rows[$dept]->total : 0;
            $completed = $rows->has($dept) ? (int) $rows[$dept]->completed : 0;

            $totalData[] = $total;
            $completedData[] = $completed;
            $completionPct[] = $total > 0 ? round(($completed / $total) * 100, 1) : 0;
        }

        return [
            'labels' => $departments,
            'datasets' => [
                [
                    'label'           => 'Total Enrollment',
                    'type'            => 'bar',
                    'data'            => $totalData,
                    'backgroundColor' => 'rgba(26, 58, 92, 0.9)',
                    'borderColor'     => 'rgba(26, 58, 92, 1)',
                    'borderWidth'     => 1,
                    'yAxisID'         => 'y',
                ],
                [
                    'label'           => 'Selesai',
                    'type'            => 'bar',
                    'data'            => $completedData,
                    'backgroundColor' => 'rgba(13, 115, 119, 0.9)',
                    'borderColor'     => 'rgba(13, 115, 119, 1)',
                    'borderWidth'     => 1,
                    'yAxisID'         => 'y',
                ],
                [
                    'label'           => 'Tingkat Penyelesaian (%)',
                    'type'            => 'line',
                    'data'            => $completionPct,
                    'borderColor'     => 'rgba(200,16,46,1)',
                    'backgroundColor' => 'rgba(200,16,46,0.1)',
                    'yAxisID'         => 'y1',
                    'fill'            => false,
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
                    'position'    => 'left',
                    'ticks'       => ['stepSize' => 1],
                ],
                'y1' => [
                    'position' => 'right',
                    'beginAtZero' => true,
                    'ticks' => ['callback' => 'function(value){return value+"%";}'],
                ],
            ],
            'plugins' => [
                'legend' => ['position' => 'top'],
            ],
        ];
    }
}
