<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Models\Branch;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $branch = Branch::with(['areaRelation.region', 'regionRelation'])->find($data['branch_id'] ?? null);

        $data['full_name'] = $data['nama_lengkap'] ?? $data['full_name'] ?? null;
        $data['area'] = $branch?->areaRelation?->nama_area ?? $branch?->area ?? ($data['area'] ?? null);
        $data['region'] = $branch?->regionRelation?->nama_region ?? $branch?->region ?? ($data['region'] ?? null);

        return $data;
    }
}
