<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LearningPath extends Model
{
    use HasUuids;

    protected $fillable = [
        'name', 'job_role_id', 'description', 'status', 'created_by',
    ];

    public function jobRole(): BelongsTo
    {
        return $this->belongsTo(JobRole::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function competencies(): BelongsToMany
    {
        return $this->belongsToMany(Competency::class, 'learning_path_competencies')
                    ->withPivot('id', 'order_index', 'is_mandatory')
                    ->orderByPivot('order_index');
    }
}
