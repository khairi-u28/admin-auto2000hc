<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Employee;

use Filament\Tables\Contracts\HasTable;

class NasionalRegionPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.nasional-region';

    protected static bool $shouldRegisterNavigation = false;

    public function mount(): void
    {
        // nothing
    }

    protected function getTableQuery(): Builder
    {
        $region = urldecode(request()->route('region') ?? request()->get('region'));

        return Employee::query()
            ->leftJoin('enrollments', 'enrollments.employee_id', '=', 'employees.id')
            ->selectRaw("employees.area as area, COUNT(DISTINCT employees.branch_id) as jumlah_cabang, COUNT(employees.id) as jumlah_karyawan, SUM(CASE WHEN enrollments.status = 'completed' THEN 1 ELSE 0 END) as enrollment_selesai")
            ->where('employees.region', $region)
            ->groupBy('employees.area');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('area')->label('Area')->searchable(),
                TextColumn::make('jumlah_cabang')->label('Jumlah Cabang')->sortable(),
                TextColumn::make('jumlah_karyawan')->label('Jumlah Karyawan')->sortable(),
                TextColumn::make('enrollment_selesai')->label('Enrollment Selesai')->sortable(),
                TextColumn::make('completion_pct')
                    ->label('% Selesai')
                    ->getStateUsing(fn ($record) => $record->jumlah_karyawan ? round(($record->enrollment_selesai / $record->jumlah_karyawan) * 100,1) . '%' : '0%')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat Area')
                    ->url(fn ($record) => url('/admin/nasional/area/' . urlencode($record->area) . '?region=' . urlencode(request()->route('region') ?? request()->get('region')))),
            ]);
    }
}
