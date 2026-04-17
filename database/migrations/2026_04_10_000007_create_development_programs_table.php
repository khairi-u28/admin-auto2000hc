<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('development_programs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('program_name')->comment('e.g. MDP SH, MDP BC, ASDP, MDP FAH');
            $table->enum('status', ['on_going', 'promoted', 'failed', 'pool_of_cadre']);
            $table->year('period_year');
            $table->decimal('kpi_mid_year', 4, 2)->nullable();
            $table->decimal('kpi_full_year', 4, 2)->nullable();
            $table->string('pk_score')->nullable()->comment('e.g. A, B, C');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('development_programs');
    }
};
