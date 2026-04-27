<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sesi extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'sesi';

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'capacity',
        'kode_batch',
        'status',
        'curriculum_id',
        'pic_employee_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function curriculum(): BelongsTo
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }

    public function pic(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'pic_employee_id');
    }

    public function peserta(): HasMany
    {
        return $this->hasMany(SesiPeserta::class, 'sesi_id');
    }

    public function trainers(): HasMany
    {
        return $this->hasMany(SesiTrainer::class, 'sesi_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(SesiRating::class, 'sesi_id');
    }

    public function getCompletionRateAttribute(): float
    {
        $totalParticipants = $this->peserta()->count();

        if ($totalParticipants === 0) {
            return 0.0;
        }

        $completedParticipants = $this->peserta()
            ->where('status', 'completed')
            ->count();

        return round(($completedParticipants / $totalParticipants) * 100, 2);
    }
}
