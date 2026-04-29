<?php

namespace App\Filament\Widgets;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\BatchParticipant;
use App\Models\TrainingRecord;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalEmployees       = Employee::where('status', 'active')->count();
        $trainingAktif      = BatchParticipant::whereIn('status', ['diundang', 'terdaftar', 'hadir'])->count();
        $trainingDone       = BatchParticipant::where('status', 'lulus')->count();
        $totalbatch_participants     = BatchParticipant::count();
        $completionRate       = $totalbatch_participants > 0
            ? round(($trainingDone / $totalbatch_participants) * 100, 1)
            : 0;
        $totalTrainingRecords = TrainingRecord::count();
        $branchCount          = Branch::count();

        // Cabang teraktif bulan ini (by training completions)
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $topBranch = BatchParticipant::query()
            ->where('batch_participants.status', 'lulus')
            ->whereBetween('batch_participants.updated_at', [$startOfMonth, $endOfMonth])
            ->join('employees', 'batch_participants.employee_id', '=', 'employees.id')
            ->join('branches', 'employees.branch_id', '=', 'branches.id')
            ->selectRaw('branches.id, branches.name, COUNT(*) as completions')
            ->groupBy('branches.id', 'branches.name')
            ->orderByDesc('completions')
            ->first();

        $topBranchLabel = $topBranch ? "{$topBranch->name} ({$topBranch->completions})" : '-';

        return [
            Stat::make('Total Karyawan Aktif', number_format($totalEmployees))
                ->description('Total karyawan aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('training Aktif', number_format($trainingAktif))
                ->description('Sedang berjalan / belum dimulai')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),

            Stat::make('Tingkat Penyelesaian', "{$completionRate}%")
                ->description("{$trainingDone} dari {$totalbatch_participants} training selesai")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($completionRate >= 70 ? 'success' : ($completionRate >= 40 ? 'warning' : 'danger')),

            Stat::make('Rekam Pelatihan', number_format($totalTrainingRecords))
                ->description("Dari {$branchCount} cabang")
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),

            Stat::make('Cabang Teraktif Bulan Ini', $topBranchLabel)
                ->description('Cabang dengan penyelesaian terbanyak bulan ini')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('primary'),
        ];
    }
}


