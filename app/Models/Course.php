<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasUuids;

    protected $fillable = [
        'title', 'department', 'type', 'description',
        'duration_minutes', 'file_path', 'external_url',
        'status', 'created_by',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'module_courses')
                    ->using(ModuleCourse::class)
                    ->withPivot('id', 'order_index', 'is_mandatory');
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
