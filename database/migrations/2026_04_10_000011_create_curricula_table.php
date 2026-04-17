<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curricula', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->enum('department', ['Sales', 'Aftersales', 'PD', 'HC', 'GS', 'Other']);
            $table->foreignUuid('job_role_id')->nullable()->constrained('job_roles')->nullOnDelete();
            $table->year('academic_year');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curricula');
    }
};
