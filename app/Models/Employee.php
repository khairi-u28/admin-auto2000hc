<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasUuids;

    protected $fillable = [
        'nrp',
        'full_name',
        'nama_lengkap',
        'position_name',
        'pos',
        'masa_bakti',
        'job_role_id',
        'branch_id',
        'area',
        'region',
        'employee_type',
        'entry_date',
        'date_of_birth',
        'hav_score',
        'hav_category',
        'grade',
        'status',
        'italent_user',
    ];

    protected function casts(): array
    {
        return [
            'entry_date'    => 'date',
            'date_of_birth' => 'date',
            'hav_score'     => 'integer',
        ];
    }

    public function jobRole(): BelongsTo
    {
        return $this->belongsTo(JobRole::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function batches(): BelongsToMany
    {
        return $this->belongsToMany(Batch::class, 'batch_employee')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function branchArea(): ?Area
    {
        return $this->branch?->areaRelation;
    }

    public function branchRegion(): ?Region
    {
        return $this->branch?->regionRelation;
    }

    public function trainingRecords(): HasMany
    {
        return $this->hasMany(TrainingRecord::class);
    }

    public function developmentPrograms(): HasMany
    {
        return $this->hasMany(DevelopmentProgram::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function getNamaLengkapAttribute(): ?string
    {
        return $this->attributes['nama_lengkap'] ?? $this->attributes['full_name'] ?? null;
    }
}
