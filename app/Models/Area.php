<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Area extends Model
{
    use HasUuids;

    protected $fillable = [
        'region_id',
        'nama_area',
        'nama_abh',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class, 'area_id');
    }

    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(Employee::class, Branch::class, 'area_id', 'branch_id');
    }
}
