<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create learning_paths and learning_path_competencies tables.
 * Part 2.3 - 2.4 of Ruang Kompetensi data model changes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_paths', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->foreignUuid('job_role_id')->constrained('job_roles')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('learning_path_competencies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('learning_path_id')->constrained('learning_paths')->cascadeOnDelete();
            $table->foreignUuid('competency_id')->constrained('competencies')->cascadeOnDelete();
            $table->unsignedTinyInteger('order_index')->default(0);
            $table->boolean('is_mandatory')->default(true);
            $table->unique(['learning_path_id', 'competency_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_path_competencies');
        Schema::dropIfExists('learning_paths');
    }
};
