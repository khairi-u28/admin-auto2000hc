<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchParticipant extends Model
{
    use HasUuids;

    protected $fillable = [
        'batch_id',
        'employee_id',
        'status',
        'participant_notes',
        'invitation_sent_at',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'invitation_sent_at' => 'datetime',
            'started_at'         => 'datetime',
            'completed_at'       => 'datetime',
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
}
