<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nrp')->unique();
            $table->string('full_name');
            $table->string('position_name');
            $table->foreignUuid('job_role_id')->constrained('job_roles');
            $table->foreignUuid('branch_id')->constrained('branches');
            $table->string('area');
            $table->string('region');
            $table->enum('employee_type', ['VSP', 'BP', 'THS', 'HO', 'Other']);
            $table->date('entry_date');
            $table->date('date_of_birth')->nullable();
            $table->tinyInteger('hav_score')->unsigned()->nullable()->comment('HAV assessment score 1-11');
            $table->string('hav_category')->nullable()->comment('e.g. Strong Performers, Candidate');
            $table->string('grade')->nullable()->comment('e.g. 4A, 4B');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('italent_user')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
