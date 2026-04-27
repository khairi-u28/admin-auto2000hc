<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class SesiRating extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'sesi_ratings';

    protected $fillable = ['sesi_id','employee_id','rating','comment'];

    public function sesi()
    {
        return $this->belongsTo(Sesi::class, 'sesi_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
