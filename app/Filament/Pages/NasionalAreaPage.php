<?php

namespace App\Filament\Pages;

use App\Models\Employee;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

use Filament\Tables\Contracts\HasTable;

class NasionalAreaPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.nasional-area';

    protected static bool $shouldRegisterNavigation = false;

    protected function getTableQuery(): Builder
    {
        $region = urldecode(request()->route('region') ?? request()->get('region'));
        $area = urldecode(request()->route('area') ?? request()->get('area'));

        return Employee::query()
            ->leftJoin('enrollments', 'enrollments.employee_id', '=', 'employees.id')
            ->leftJoin('branches', 'branches.id', '=', 'employees.branch_id')
            ->selectRaw("employees.branch_id as branch_id, branches.name as branch_name, COUNT(employees.id) as jumlah_karyawan, SUM(CASE WHEN enrollments.status = 'completed' THEN 1 ELSE 0 END) as enrollment_selesai")
            ->where('employees.region', $region)
            ->where('employees.area', $area)
            ->groupBy('employees.branch_id', 'branches.name');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->defaultKeySort(false)
            ->columns([
                TextColumn::make('branch_name')->label('Cabang')->searchable()->sortable(),
                TextColumn::make('jumlah_karyawan')->label('Jumlah Karyawan')->sortable(),
                TextColumn::make('enrollment_selesai')->label('Enrollment Selesai')->sortable(),
                TextColumn::make('completion_pct')
                    ->label('% Selesai')
                    ->getStateUsing(fn ($record) => $record->jumlah_karyawan ? round(($record->enrollment_selesai / $record->jumlah_karyawan) * 100,1) . '%' : '0%')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat Cabang')
                    ->url(fn ($record) => url('/admin/nasional/cabang/' . $record->branch_id . '?area=' . urlencode(request()->route('area') ?? request()->get('area')) . '&region=' . urlencode(request()->route('region') ?? request()->get('region')))),
            ]);
    }
}
