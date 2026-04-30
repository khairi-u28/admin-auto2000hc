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

class NasionalCabangPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static bool $shouldRegisterNavigation = false;

    public function getView(): string
    {
        return 'filament.pages.nasional-cabang';
    }

    protected function getTableQuery(): Builder
    {
        $branchId = request()->route('branch') ?? request()->get('branch') ?? request()->get('branch_id');

        return Employee::query()
            ->where('employees.branch_id', $branchId)
            ->leftJoin('job_roles', 'job_roles.id', '=', 'employees.job_role_id')
            ->select('employees.id', 'employees.nrp', 'employees.full_name', 'job_roles.name as job_role', 'employees.status', 'employees.entry_date')
            ->orderBy('employees.full_name');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('nrp')->label('NRP')->sortable(),
                TextColumn::make('full_name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('job_role')->label('Jabatan')->sortable(),
                TextColumn::make('status')->label('Status')->sortable(),
                TextColumn::make('entry_date')->label('Tgl Masuk')->date('d/m/Y')->sortable(),
            ])
            ->recordActions([
                Action::make('onesheet')
                    ->label('One Sheet')
                    ->url(fn ($record) => \App\Filament\Pages\OneSheetProfilePage::getUrl(['employee' => $record->id])),
            ]);
    }
}


