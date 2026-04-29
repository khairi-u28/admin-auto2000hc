<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchMateri extends Model
{
    use HasUuids;

    protected $table = 'batch_materi';

    protected $fillable = [
        'batch_id',
        'module_id',
        'course_id',
        'order_index',
        'session_datetime',
        'session_link',
        'session_venue',
        'session_notes',
    ];

    protected function casts(): array
    {
        return [
            'session_datetime' => 'datetime',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
