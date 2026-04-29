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
        'batch_code',
        'name',
        'type',
        'competency_id',
        'branch_id',
        'pic_id',
        'start_date',
        'end_date',
        'target_participants',
        'status',
        'evaluation_notes',
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

    public function participants(): HasMany
    {
        return $this->hasMany(BatchParticipant::class);
    }

    public function materi(): HasMany
    {
        return $this->hasMany(BatchMateri::class)->orderBy('order_index');
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(BatchFeedback::class);
    }

    public function participantProgress(): HasMany
    {
        return $this->hasMany(ParticipantMateriProgress::class);
    }

    /** Count of participants who have reached at least 'terdaftar' status */
    public function getAktualPesertaAttribute(): int
    {
        return $this->participants()
            ->whereNotIn('status', ['menunggu_undangan', 'diundang'])
            ->count();
    }

    /** Generate next batch code: BATCH-{TYPE}-{YEAR}-{seq:003} */
    public static function generateCode(string $type, int $year): string
    {
        $prefix = "BATCH-{$type}-{$year}-";
        $lastSeq = static::where('batch_code', 'like', $prefix . '%')
            ->orderBy('batch_code', 'desc')
            ->value('batch_code');

        $seq = 1;
        if ($lastSeq) {
            $seq = (int) substr($lastSeq, strlen($prefix)) + 1;
        }

        return $prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }
}
