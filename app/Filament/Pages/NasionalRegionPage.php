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

    protected static bool $shouldRegisterNavigation = false;

    public function getView(): string
    {
        return 'filament.pages.nasional-region';
    }

    public function mount(): void
    {
        // nothing
    }

    protected function getTableQuery(): Builder
    {
        $region = urldecode(request()->route('region') ?? request()->get('region'));

        return Employee::query()
            ->leftJoin('batch_participants', 'batch_participants.employee_id', '=', 'employees.id')
            ->selectRaw("employees.area as id, employees.area as area, COUNT(DISTINCT employees.branch_id) as jumlah_cabang, COUNT(employees.id) as jumlah_karyawan, SUM(CASE WHEN batch_participants.status = 'lulus' THEN 1 ELSE 0 END) as training_selesai")
            ->where('employees.region', $region)
            ->groupBy('employees.area');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())

            ->defaultKeySort(false)
            ->columns([
                TextColumn::make('area')->label('Area')->searchable(),
                TextColumn::make('jumlah_cabang')->label('Jumlah Cabang')->sortable(),
                TextColumn::make('jumlah_karyawan')->label('Jumlah Karyawan')->sortable(),
                TextColumn::make('training_selesai')->label('training Selesai')->sortable(),
                TextColumn::make('completion_pct')
                    ->label('% Selesai')
                    ->getStateUsing(fn ($record) => $record->jumlah_karyawan ? round(($record->training_selesai / $record->jumlah_karyawan) * 100,1) . '%' : '0%')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('lihat')
                    ->label('Lihat Area')
                    ->url(fn ($record) => url('/admin/nasional/area/' . urlencode($record->area) . '?region=' . urlencode(request()->route('region') ?? request()->get('region')))),
            ]);
    }
}




