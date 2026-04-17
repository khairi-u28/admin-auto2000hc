<?php

namespace App\Filament\Widgets;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\TrainingRecord;
use Filament\Schemas\Schema;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function content(Schema $schema): Schema
    {
        $totalEmployees     = Employee::where('status', 'active')->count();
        $branchCount        = Branch::count();
        $enrollmentOngoing  = Enrollment::whereIn('status', ['in_progress', 'not_started'])->count();
        $enrollmentDone     = Enrollment::where('status', 'completed')->count();
        $totalEnrollments   = Enrollment::count();
        $completionRate     = $totalEnrollments > 0
            ? round(($enrollmentDone / $totalEnrollments) * 100, 1)
            : 0;
        $totalTrainingRecords = TrainingRecord::count();

        return $schema->components([
            Stat::make('Karyawan Aktif', number_format($totalEmployees))
                ->description('Total karyawan aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Enrollment Aktif', number_format($enrollmentOngoing))
                ->description('Sedang berjalan / belum dimulai')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),

            Stat::make('Tingkat Penyelesaian', "{$completionRate}%")
                ->description("{$enrollmentDone} dari {$totalEnrollments} enrollment selesai")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($completionRate >= 70 ? 'success' : ($completionRate >= 40 ? 'warning' : 'danger')),

            Stat::make('Rekam Pelatihan', number_format($totalTrainingRecords))
                ->description("Dari {$branchCount} cabang")
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),
        ]);
    }
}
