<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasUuids;

    protected $fillable = [
        'nrp', 'full_name', 'position_name', 'job_role_id', 'branch_id',
        'area', 'region', 'employee_type', 'entry_date', 'date_of_birth',
        'hav_score', 'hav_category', 'grade', 'status', 'italent_user',
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

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
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
}
