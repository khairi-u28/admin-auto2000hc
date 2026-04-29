<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempt extends Model
{
    use HasUuids;

    protected $fillable = [
        'batch_participant_id', 'course_id', 'attempt_number',
        'score', 'passed', 'answers', 'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'answers'      => 'array',
            'passed'       => 'boolean',
            'submitted_at' => 'datetime',
            'score'        => 'integer',
        ];
    }

    public function batchParticipant(): BelongsTo
    {
        return $this->belongsTo(BatchParticipant::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
