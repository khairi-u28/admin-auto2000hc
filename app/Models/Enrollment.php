<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasUuids;

    protected $fillable = [
        'employee_id',
        'curriculum_id',
        'enrolled_by',
        'status',
        'progress_pct',
        'due_date',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'progress_pct' => 'integer',
            'due_date'     => 'date',
            'started_at'   => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function enrolledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'enrolled_by');
    }
}
