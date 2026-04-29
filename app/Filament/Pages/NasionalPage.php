<?php

namespace App\Filament\Pages;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Enrollment;
use App\Models\TrainingRecord;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use Filament\Tables\Contracts\HasTable;

class NasionalPage extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected string $view = 'filament.pages.nasional';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-globe-asia-australia';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return 'Laporan';
    }

    public function getNationalStats(): array
    {
        $totalEnrollments = Enrollment::count();
        $completedEnrollments = Enrollment::where('status', 'completed')->count();

        return [
            'total_karyawan_aktif' => Employee::where('status', 'active')->count(),
            'total_cabang' => Branch::count(),
            'total_enrollment' => $totalEnrollments,
            'completion_rate' => $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 1) : 0,
            'total_training_records' => TrainingRecord::count(),
        ];
    }

    public function getEnrollmentStatusDistribution(): array
    {
        $statuses = ['not_started', 'in_progress', 'completed', 'overdue'];

        return collect($statuses)
            ->mapWithKeys(fn (string $status) => [$status => Enrollment::where('status', $status)->count()])
            ->all();
    }

    public function getMonthlyCompletionTrend(): array
    {
        return collect(range(5, 0))
            ->map(function (int $monthsAgo): array {
                $date = now()->subMonths($monthsAgo);

                return [
                    'label' => $date->format('M Y'),
                    'value' => Enrollment::query()
                        ->where('status', 'completed')
                        ->whereYear('completed_at', $date->year)
                        ->whereMonth('completed_at', $date->month)
                        ->count(),
                ];
            })
            ->push([
                'label' => now()->format('M Y'),
                'value' => Enrollment::query()
                    ->where('status', 'completed')
                    ->whereYear('completed_at', now()->year)
                    ->whereMonth('completed_at', now()->month)
                    ->count(),
            ])
            ->all();
    }

    public function getRegionCompletionData(): array
    {
        return Employee::query()
            ->leftJoin('enrollments', 'enrollments.employee_id', '=', 'employees.id')
            ->selectRaw(implode(', ', [
                'employees.region as region',
                'COUNT(DISTINCT employees.id) as total_karyawan',
                "COALESCE(SUM(CASE WHEN enrollments.status = 'completed' THEN 1 ELSE 0 END), 0) as completed_enrollments",
            ]))
            ->whereNotNull('employees.region')
            ->groupBy('employees.region')
            ->orderBy('employees.region')
            ->get()
            ->map(function ($row): array {
                $rate = $row->total_karyawan > 0
                    ? round(($row->completed_enrollments / $row->total_karyawan) * 100, 1)
                    : 0;

                return [
                    'region' => $row->region,
                    'total_karyawan' => (int) $row->total_karyawan,
                    'completed_enrollments' => (int) $row->completed_enrollments,
                    'rate' => $rate,
                ];
            })
            ->all();
    }

    protected function getTableQuery(): Builder
    {
        return Employee::query()
            ->selectRaw(
                implode(', ', [
                    'employees.region as region',
                    'COUNT(DISTINCT employees.area) as jumlah_area',
                    'COUNT(DISTINCT employees.branch_id) as jumlah_cabang',
                    'COUNT(DISTINCT employees.id) as jumlah_karyawan',
                    "COALESCE(SUM(CASE WHEN enrollments.status = 'completed' THEN 1 ELSE 0 END), 0) as enrollment_selesai",
                ])
            )
            ->leftJoin('enrollments', 'enrollments.employee_id', '=', 'employees.id')
            ->whereNotNull('employees.region')
            ->groupBy('employees.region');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->defaultSort('region', 'asc')
            ->defaultKeySort(false)
            ->columns([
                TextColumn::make('region')->label('Region')->searchable()->sortable(),
                TextColumn::make('jumlah_area')->label('Jumlah Area')->sortable(),
                TextColumn::make('jumlah_cabang')->label('Jumlah Cabang')->sortable(),
                TextColumn::make('jumlah_karyawan')->label('Jumlah Karyawan')->sortable(),
                TextColumn::make('enrollment_selesai')->label('Enrollment Selesai')->sortable(),
                TextColumn::make('completion_pct')
                    ->label('% Selesai')
                    ->getStateUsing(fn ($record) => $record->jumlah_karyawan ? round(($record->enrollment_selesai / $record->jumlah_karyawan) * 100,1) . '%' : '0%'),
            ])
            ->filters([
                SelectFilter::make('region')
                    ->label('Cari Region')
                    ->options(fn () => Employee::query()->whereNotNull('region')->orderBy('region')->distinct()->pluck('region', 'region')->toArray())
                    ->searchable(),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat Region')
                    ->url(fn ($record) => url('/admin/nasional/region/' . urlencode($record->region))),
            ]);
    }
}
