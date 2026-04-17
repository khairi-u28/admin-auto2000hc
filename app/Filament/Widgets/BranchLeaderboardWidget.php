<?php

namespace App\Filament\Widgets;

use App\Models\Branch;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class BranchLeaderboardWidget extends TableWidget
{
    protected static ?string $heading = 'Leaderboard Cabang — Tingkat Penyelesaian';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Branch::query()
            ->withCount([
                'employees',
                'employees as completed_enrollments_count' => function (Builder $query) {
                    $query->whereHas('enrollments', fn (Builder $q) =>
                        $q->where('status', 'completed')
                    );
                },
            ])
            ->orderByDesc('completed_enrollments_count');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('name')
                    ->label('Cabang')
                    ->searchable(),
                TextColumn::make('region')
                    ->label('Region'),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'GR'    => 'primary',
                        'BP'    => 'warning',
                        'HO'    => 'success',
                        default => 'secondary',
                    }),
                TextColumn::make('employees_count')
                    ->label('Jml. Karyawan')
                    ->sortable(),
                TextColumn::make('completed_enrollments_count')
                    ->label('Karyawan Selesai Enrollment')
                    ->sortable(),
                TextColumn::make('completion_pct')
                    ->label('% Selesai')
                    ->state(function (Branch $record): string {
                        if ($record->employees_count === 0) {
                            return '0%';
                        }

                        return round(($record->completed_enrollments_count / $record->employees_count) * 100, 1) . '%';
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw(
                            'IF(employees_count = 0, 0, completed_enrollments_count / employees_count) ' . $direction
                        );
                    }),
            ])
            ->paginated([10, 25, 50]);
    }
}
