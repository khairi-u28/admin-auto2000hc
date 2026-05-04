<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasUuids;

    protected $fillable = [
        'batch_code',
        'name',
        'type',
        'competency_id',
        'branch_id',
        'area_penyelenggara',
        'pic_id',
        'start_date',
        'end_date',
        'target_participants',
        'status',
        'description',
        'evaluation',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
        ];
    }

    public function competency(): BelongsTo
    {
        return $this->belongsTo(Competency::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function materi(): HasMany
    {
        return $this->hasMany(BatchMateri::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(BatchParticipant::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(BatchFeedback::class);
    }

    public function trainers(): HasMany
    {
        return $this->hasMany(Employee::class, 'id', 'pic_id');
    }

    public function evaluationRelation(): HasMany
    {
        return $this->hasMany(Batch::class, 'id', 'id');
    }

    public function getActualParticipantsCountAttribute(): int
    {
        return $this->participants()
            ->whereNotIn('status', ['menunggu_undangan', 'diundang'])
            ->count();
    }

    public static function generateCode(string $type): string
    {
        $year = now()->year;
        $prefix = "BATCH-{$type}-{$year}";
        $last = static::where('batch_code', 'like', "{$prefix}-%")
            ->orderByDesc('batch_code')
            ->value('batch_code');
        $seq = $last ? (int) substr($last, -3) + 1 : 1;
        return $prefix . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }
}
