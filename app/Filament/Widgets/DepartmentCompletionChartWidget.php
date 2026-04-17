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
        $data = Enrollment::query()
            ->join('curricula', 'enrollments.curriculum_id', '=', 'curricula.id')
            ->select(
                'curricula.department',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN enrollments.status = \'completed\' THEN 1 ELSE 0 END) as completed')
            )
            ->groupBy('curricula.department')
            ->orderBy('curricula.department')
            ->get();

        return [
            'datasets' => [
                [
                    'label'           => 'Total Enrollment',
                    'data'            => $data->pluck('total')->toArray(),
                    'backgroundColor' => 'rgba(26, 58, 92, 0.5)',
                    'borderColor'     => 'rgba(26, 58, 92, 1)',
                    'borderWidth'     => 1,
                ],
                [
                    'label'           => 'Selesai',
                    'data'            => $data->pluck('completed')->toArray(),
                    'backgroundColor' => 'rgba(27, 122, 78, 0.5)',
                    'borderColor'     => 'rgba(27, 122, 78, 1)',
                    'borderWidth'     => 1,
                ],
            ],
            'labels' => $data->pluck('department')->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks'       => ['stepSize' => 1],
                ],
            ],
            'plugins' => [
                'legend' => ['position' => 'top'],
            ],
        ];
    }
}
