<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class SesiTrainer extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'sesi_trainers';

    protected $fillable = ['sesi_id','employee_id','name','role'];

    public function sesi()
    {
        return $this->belongsTo(Sesi::class, 'sesi_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getTrainerNameAttribute(): ?string
    {
        return $this->employee?->nama_lengkap ?? $this->employee?->full_name ?? $this->name;
    }
}
