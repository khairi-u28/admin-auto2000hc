<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingRecord extends Model
{
    use HasUuids;

    protected $fillable = [
        'employee_id', 'competency_track_id', 'level_achieved',
        'completion_date', 'certification_number', 'certification_expiry',
        'notes', 'source', 'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'completion_date'    => 'date',
            'certification_expiry' => 'date',
            'level_achieved'     => 'integer',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function competencyTrack(): BelongsTo
    {
        return $this->belongsTo(CompetencyTrack::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
