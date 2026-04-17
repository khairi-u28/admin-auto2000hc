<?php

namespace App\Filament\Widgets;

use App\Models\RoleCompetencyRequirement;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CompetencyGapWidget extends TableWidget
{
    protected static ?string $heading = 'Analisis Kesenjangan Kompetensi';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return RoleCompetencyRequirement::query()
            ->join('competency_tracks', 'role_competency_requirements.competency_track_id', '=', 'competency_tracks.id')
            ->join('job_roles', 'role_competency_requirements.job_role_id', '=', 'job_roles.id')
            ->leftJoin('employees', 'employees.job_role_id', '=', 'role_competency_requirements.job_role_id')
            ->leftJoin('training_records', function ($join) {
                $join->on('training_records.employee_id', '=', 'employees.id')
                    ->on('training_records.competency_track_id', '=', 'role_competency_requirements.competency_track_id');
            })
            ->select([
                'role_competency_requirements.id',
                'job_roles.name as job_role_name',
                'competency_tracks.name as competency_name',
                'role_competency_requirements.minimum_level',
                DB::raw('COUNT(DISTINCT employees.id) as total_employees'),
                DB::raw('COUNT(DISTINCT CASE WHEN training_records.level_achieved >= role_competency_requirements.minimum_level THEN employees.id END) as employees_met'),
            ])
            ->groupBy([
                'role_competency_requirements.id',
                'role_competency_requirements.job_role_id',
                'role_competency_requirements.competency_track_id',
                'role_competency_requirements.minimum_level',
                'job_roles.name',
                'competency_tracks.name',
            ])
            ->orderByRaw('(total_employees - employees_met) DESC');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('job_role_name')
                    ->label('Jabatan')
                    ->searchable(query: fn (Builder $query, string $search): Builder =>
                        $query->where('job_roles.name', 'like', "%{$search}%")
                    ),
                TextColumn::make('competency_name')
                    ->label('Kompetensi')
                    ->searchable(query: fn (Builder $query, string $search): Builder =>
                        $query->where('competency_tracks.name', 'like', "%{$search}%")
                    ),
                TextColumn::make('minimum_level')
                    ->label('Level Min.')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'warning',
                        2 => 'primary',
                        3 => 'success',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (int $state): string => "Level {$state}"),
                TextColumn::make('total_employees')
                    ->label('Jml. Karyawan'),
                TextColumn::make('employees_met')
                    ->label('Sudah Memenuhi'),
                TextColumn::make('gap')
                    ->label('Kesenjangan')
                    ->state(fn ($record): int => max(0, $record->total_employees - $record->employees_met))
                    ->color(fn ($record): string => ($record->total_employees - $record->employees_met) > 0 ? 'danger' : 'success'),
                TextColumn::make('gap_pct')
                    ->label('% Gap')
                    ->state(function ($record): string {
                        if ($record->total_employees === 0) {
                            return 'N/A';
                        }
                        $gap = $record->total_employees - $record->employees_met;

                        return round(($gap / $record->total_employees) * 100, 1) . '%';
                    }),
            ])
            ->paginated([15, 30, 50]);
    }
}
