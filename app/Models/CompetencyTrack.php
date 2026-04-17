<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompetencyTrack extends Model
{
    use HasUuids;

    protected $fillable = [
        'code', 'name', 'department', 'category', 'level_sequence', 'description',
    ];

    public function roleRequirements(): HasMany
    {
        return $this->hasMany(RoleCompetencyRequirement::class);
    }

    public function trainingRecords(): HasMany
    {
        return $this->hasMany(TrainingRecord::class);
    }
}
