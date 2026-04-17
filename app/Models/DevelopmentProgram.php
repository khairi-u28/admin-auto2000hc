<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevelopmentProgram extends Model
{
    use HasUuids;

    protected $fillable = [
        'employee_id', 'program_name', 'status', 'period_year',
        'kpi_mid_year', 'kpi_full_year', 'pk_score',
    ];

    protected function casts(): array
    {
        return [
            'kpi_mid_year'  => 'decimal:2',
            'kpi_full_year' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
