<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_competency_requirements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('job_role_id')->constrained('job_roles')->cascadeOnDelete();
            $table->foreignUuid('competency_track_id')->constrained('competency_tracks')->cascadeOnDelete();
            $table->boolean('is_mandatory')->default(true);
            $table->tinyInteger('minimum_level')->unsigned()->default(1);
            $table->timestamps();

            $table->unique(['job_role_id', 'competency_track_id'], 'rcr_role_track_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_competency_requirements');
    }
};
