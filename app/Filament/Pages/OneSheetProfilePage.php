<?php

namespace App\Filament\Pages;

use App\Models\Employee;
use Filament\Pages\Page;

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
            'trainingRecords.competencyTrack',
            'developmentPrograms',
            'enrollments.curriculum',
        ])->findOrFail($id);
    }

    public function getTitle(): string
    {
        return "One Sheet: {$this->employee->full_name}";
    }

    public function getView(): string
    {
        return 'filament.pages.one-sheet-profile';
    }
}
