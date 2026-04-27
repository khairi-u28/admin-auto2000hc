<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasUuids;

    protected $fillable = [
        'kode_batch',
        'nama_batch',
        'curriculum_id',
        'pic_employee_id',
        'status',
        'active_from',
        'active_until',
    ];

    protected function casts(): array
    {
        return [
            'active_from' => 'date',
            'active_until' => 'date',
        ];
    }

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function pic(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'pic_employee_id');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'batch_employee')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function peserta(): BelongsToMany
    {
        return $this->participants()->wherePivot('role', 'peserta');
    }

    public function trainers(): BelongsToMany
    {
        return $this->participants()->wherePivot('role', 'trainer');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(BatchReview::class);
    }

    public function getCompletionRateAttribute(): float
    {
        $participantIds = $this->peserta()->pluck('employees.id');
        $totalParticipants = $participantIds->count();

        if ($totalParticipants === 0) {
            return 0.0;
        }

        $completedParticipants = Enrollment::query()
            ->whereIn('employee_id', $participantIds)
            ->where('status', 'completed')
            ->distinct('employee_id')
            ->count('employee_id');

        return round(($completedParticipants / $totalParticipants) * 100, 2);
    }
}
