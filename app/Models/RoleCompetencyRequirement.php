<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleCompetencyRequirement extends Model
{
    use HasUuids;

    protected $fillable = [
        'job_role_id', 'competency_track_id', 'is_mandatory', 'minimum_level',
    ];

    protected function casts(): array
    {
        return [
            'is_mandatory'  => 'boolean',
            'minimum_level' => 'integer',
        ];
    }

    public function jobRole(): BelongsTo
    {
        return $this->belongsTo(JobRole::class);
    }

    public function competencyTrack(): BelongsTo
    {
        return $this->belongsTo(CompetencyTrack::class);
    }
}
