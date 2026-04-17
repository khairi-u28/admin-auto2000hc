<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasUuids;

    protected $fillable = [
        'code', 'name', 'region', 'area', 'type',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
