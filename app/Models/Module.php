<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Module extends Model
{
    use HasUuids;

    protected $fillable = [
        'title', 'department', 'description', 'status', 'created_by',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'module_courses')
                    ->using(ModuleCourse::class)
                    ->withPivot('id', 'order_index', 'is_mandatory');
    }

    public function competencies(): BelongsToMany
    {
        return $this->belongsToMany(Competency::class, 'competency_modules')
                    ->withPivot('id', 'order_index', 'is_mandatory');
    }
}
