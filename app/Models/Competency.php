<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competency extends Model
{
    use HasUuids;

    protected $table = 'competencies';

    protected $fillable = [
        'code', 'name', 'description', 'tags',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
        ];
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'competency_modules')
                    ->withPivot('id', 'order_index', 'is_mandatory')
                    ->orderByPivot('order_index');
    }

    public function learningPaths(): BelongsToMany
    {
        return $this->belongsToMany(LearningPath::class, 'learning_path_competencies')
                    ->withPivot('id', 'order_index', 'is_mandatory')
                    ->orderByPivot('order_index');
    }

    public function roleRequirements(): HasMany
    {
        return $this->hasMany(RoleCompetencyRequirement::class);
    }

    public function trainingRecords(): HasMany
    {
        return $this->hasMany(TrainingRecord::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }
}
