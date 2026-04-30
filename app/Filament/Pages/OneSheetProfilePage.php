<?php

namespace App\Filament\Pages;

use App\Models\Employee;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;

class OneSheetProfilePage extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    public Employee $employee;

    public function mount(): void
    {
        $id = request('employee');
        abort_unless($id, 404);

        $this->employee = Employee::with([
            'jobRole',
            'branch',
            'trainingRecords.competency',
            'developmentPrograms',
            'batchParticipants.batch.competency',
        ])->findOrFail($id);
    }

    public function getTitle(): string
    {
        return "One Sheet: {$this->employee->nama_lengkap}";
    }

    public function getView(): string
    {
        return 'filament.pages.one-sheet-profile';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Jika menggunakan relasi ke batch/sesi
                \App\Models\BatchParticipant::query()->where('employee_id', $this->record->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at') // Atau field tanggal yang sesuai
                    ->label('Tgl Training')
                    ->date('d M Y'),
                Tables\Columns\TextColumn::make('batch.name') // Pastikan ada relasi ke tabel Batch
                    ->label('Nama Training')
                    ->description(fn($record) => $record->batch->type ?? 'Training Cabang'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
            ]);
    }
}
