<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobRole extends Model
{
    use HasUuids;

    protected $fillable = [
        'code', 'name', 'department', 'level', 'golongan',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function competencyRequirements(): HasMany
    {
        return $this->hasMany(RoleCompetencyRequirement::class);
    }

    public function learningPaths(): HasMany
    {
        return $this->hasMany(LearningPath::class);
    }
}
