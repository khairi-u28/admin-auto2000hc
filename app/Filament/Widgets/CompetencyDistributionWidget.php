<?php

namespace App\Filament\Widgets;

use App\Models\TrainingRecord;
use Filament\Widgets\ChartWidget;

class CompetencyDistributionWidget extends ChartWidget
{
    protected ?string $heading = 'Distribusi Level Kompetensi';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $rows = TrainingRecord::query()
            ->selectRaw('COALESCE(level_achieved, 0) as level, COUNT(*) as total')
            ->groupBy('level')
            ->get()
            ->keyBy('level');

        $levels = [0,1,2,3];
        $data = [];
        foreach ($levels as $l) {
            $data[] = $rows->has($l) ? (int) $rows[$l]->total : 0;
        }

        return [
            'labels' => ['Level 0','Level 1','Level 2','Level 3 (Certified)'],
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(107,114,128,1)',    // gray
                        'rgba(217,119,6,1)',      // amber
                        'rgba(29,78,216,1)',      // blue
                        'rgba(27,122,78,1)',      // green
                    ],
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['position' => 'bottom'],
                'tooltip' => ['enabled' => true],
            ],
            'cutout' => '60%'
        ];
    }
}
