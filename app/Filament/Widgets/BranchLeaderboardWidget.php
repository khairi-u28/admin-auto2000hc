<?php

namespace App\Filament\Widgets;

use App\Models\Branch;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

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
                'employees as completed_batch_participants_count' => function (Builder $query) {
                    $query->whereHas('batchParticipants', fn (Builder $q) =>
                        $q->where('status', 'lulus')
                    );
                },
            ])
            ->orderByRaw('IF(employees_count = 0, 0, completed_batch_participants_count / employees_count) DESC');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('rank')
                    ->label('Rank')
                    ->getStateUsing(function (Branch $record) {
                        static $ordered = null;

                        if ($ordered === null) {
                            $ordered = Branch::query()
                                ->withCount(['employees', 'employees as completed_batch_participants_count' => function ($q) { $q->whereHas('batchParticipants', fn($q)=> $q->where('status', 'lulus')); }])
                                ->get()
                                ->sortByDesc(function ($b) { return $b->employees_count ? ($b->completed_batch_participants_count / $b->employees_count) : 0; })
                                ->pluck('id')
                                ->toArray();
                        }

                        $idx = array_search($record->id, $ordered);
                        $rank = ($idx === false) ? '?' : $idx + 1;

                        return match ($rank) {
                            1 => "🥇 #1",
                            2 => "🥈 #2",
                            3 => "🥉 #3",
                            default => "#{$rank}",
                        };
                    }),
                TextColumn::make('name')
                    ->label('Cabang')
                    ->searchable(),
                TextColumn::make('region')
                    ->label('Region'),
                TextColumn::make('area')
                    ->label('Area'),
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
                TextColumn::make('completed_batch_participants_count')
                    ->label('Selesai training')
                    ->sortable(),
                TextColumn::make('completion_pct')
                    ->label('% Selesai')
                    ->state(function (Branch $record): string {
                        if ($record->employees_count === 0) {
                            return '0%';
                        }

                        return round(($record->completed_batch_participants_count / $record->employees_count) * 100, 1) . '%';
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw(
                            'IF(employees_count = 0, 0, completed_batch_participants_count / employees_count) ' . $direction
                        );
                    }),
                TextColumn::make('sparkline')
                    ->label('')
                    ->getStateUsing(function (Branch $record) {
                        if ($record->employees_count === 0) return '';
                        $pct = round(($record->completed_batch_participants_count / $record->employees_count) * 10); // scale 0-10
                        return str_repeat('▮', $pct) . str_repeat('▯', 10 - $pct);
                    }),
            ])
            ->paginated([10, 25, 50]);
    }
}

