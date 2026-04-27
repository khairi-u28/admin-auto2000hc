<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasUuids;

    protected $fillable = [
        'code',
        'name',
        'region',
        'area',
        'type',
        'region_id',
        'area_id',
        'kode_cabang',
        'nama',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function regionRelation(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function areaRelation(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function getKodeCabangAttribute(): ?string
    {
        return $this->attributes['kode_cabang'] ?? $this->attributes['code'] ?? null;
    }

    public function getNamaAttribute(): ?string
    {
        return $this->attributes['nama'] ?? $this->attributes['name'] ?? null;
    }
}
