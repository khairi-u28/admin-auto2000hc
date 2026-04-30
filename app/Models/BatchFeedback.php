<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchFeedback extends Model
{
    use HasUuids;

    protected $table = 'batch_feedback';

    protected $fillable = [
        'batch_id', 'employee_id',
        'training_relevance', 'training_material_quality', 'training_schedule',
        'training_facility', 'training_comments',
        'trainer_mastery', 'trainer_delivery', 'trainer_responsiveness',
        'trainer_attitude', 'trainer_comments',
        'is_submitted', 'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'is_submitted' => 'boolean',
            'submitted_at' => 'datetime',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /** Average training rating (1-5) across 4 rating dimensions */
    public function getTrainingAvgAttribute(): float
    {
        $scores = array_filter([
            $this->training_relevance,
            $this->training_material_quality,
            $this->training_schedule,
            $this->training_facility,
        ]);
        return count($scores) ? round(array_sum($scores) / count($scores), 1) : 0;
    }

    /** Average trainer rating (1-5) across 4 rating dimensions */
    public function getTrainerAvgAttribute(): float
    {
        $scores = array_filter([
            $this->trainer_mastery,
            $this->trainer_delivery,
            $this->trainer_responsiveness,
            $this->trainer_attitude,
        ]);
        return count($scores) ? round(array_sum($scores) / count($scores), 1) : 0;
    }
}
