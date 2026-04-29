<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingRecord extends Model
{
    use HasUuids;

    protected $fillable = [
        'employee_id',
        'competency_id',
        'level_achieved',
        'completion_date',
        'certification_number',
        'certification_expiry',
        'notes',
        'source',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'completion_date'    => 'date',
            'certification_expiry' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function competency(): BelongsTo
    {
        return $this->belongsTo(Competency::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
