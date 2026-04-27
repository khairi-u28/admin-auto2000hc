<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Models\Branch;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $branch = Branch::with(['areaRelation.region', 'regionRelation'])->find($data['branch_id'] ?? null);

        $data['full_name'] = $data['nama_lengkap'] ?? $data['full_name'] ?? null;
        $data['area'] = $branch?->areaRelation?->nama_area ?? $branch?->area ?? ($data['area'] ?? null);
        $data['region'] = $branch?->regionRelation?->nama_region ?? $branch?->region ?? ($data['region'] ?? null);

        return $data;
    }
}
