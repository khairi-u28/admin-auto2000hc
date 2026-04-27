<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BatchReview extends Model
{
    use HasUuids;

    protected $fillable = [
        'batch_id',
        'reviewable_type',
        'reviewable_id',
        'rating',
        'catatan',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }
}
