<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class SesiPeserta extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'sesi_peserta';

    protected $fillable = ['sesi_id','employee_id','name','email','status'];

    protected $appends = [
        'employee_name',
        'employee_nrp',
        'employee_position',
        'employee_branch',
    ];

    public function sesi()
    {
        return $this->belongsTo(Sesi::class, 'sesi_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getEmployeeNameAttribute(): ?string
    {
        return $this->employee?->nama_lengkap ?? $this->employee?->full_name ?? $this->name;
    }

    public function getEmployeeNrpAttribute(): ?string
    {
        return $this->employee?->nrp;
    }

    public function getEmployeePositionAttribute(): ?string
    {
        return $this->employee?->position_name;
    }

    public function getEmployeeBranchAttribute(): ?string
    {
        return $this->employee?->branch?->nama ?? $this->employee?->branch?->name;
    }
}
