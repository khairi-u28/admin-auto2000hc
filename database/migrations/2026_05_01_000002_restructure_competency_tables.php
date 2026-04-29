<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Restructure competency-related tables.
 * Part 2.2 - 2.5 of Ruang Kompetensi data model changes.
 *
 * Steps:
 *  1. Drop old batch/enrollment tables that reference competency_tracks
 *  2. Drop role_competency_requirements, training_records (will be recreated later)
 *  3. Rename competency_tracks → competencies and restructure
 *  4. Rename curriculum_modules → competency_modules (pivot referencing competencies)
 *  5. Recreate role_competency_requirements with new FK
 *  6. Recreate training_records with new FK
 *  7. Drop old batch-related tables (replaced in later migration)
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Drop old batch/pivot tables that have FKs we need to remove ──
        Schema::dropIfExists('batch_reviews');
        Schema::dropIfExists('batch_employee');
        Schema::dropIfExists('batches');

        // Drop enrollments/quiz_attempts (replaced by batch_participants system)
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('enrollments');

        // Drop sesi-related tables — child tables first (FK order)
        Schema::dropIfExists('sesi_ratings');
        Schema::dropIfExists('sesi_trainers');  // actual table name from migration
        Schema::dropIfExists('sesi_peserta');
        Schema::dropIfExists('sesi');

        // Drop curriculum_modules (will recreate as competency_modules)
        Schema::dropIfExists('curriculum_modules');

        // Drop training_records and role_competency_requirements (reference competency_tracks)
        Schema::dropIfExists('training_records');
        Schema::dropIfExists('role_competency_requirements');

        // ── 2. Rename + restructure competency_tracks → competencies ──
        Schema::rename('competency_tracks', 'competencies');

        Schema::table('competencies', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['department', 'category', 'level_sequence']);
            // Add new columns
            $table->json('tags')->nullable()->after('description');
        });

        // ── 3. Create competency_modules pivot (replaces curriculum_modules) ──
        Schema::create('competency_modules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('competency_id')->constrained('competencies')->cascadeOnDelete();
            $table->foreignUuid('module_id')->constrained('modules')->cascadeOnDelete();
            $table->unsignedTinyInteger('order_index')->default(0);
            $table->boolean('is_mandatory')->default(true);
            $table->unique(['competency_id', 'module_id']);
        });

        // ── 4. Recreate role_competency_requirements with competency_id FK ──
        Schema::create('role_competency_requirements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('job_role_id')->constrained('job_roles')->cascadeOnDelete();
            $table->foreignUuid('competency_id')->constrained('competencies')->cascadeOnDelete();
            $table->boolean('is_mandatory')->default(true);
            $table->unsignedTinyInteger('minimum_level')->default(1);
            $table->timestamps();
            $table->unique(['job_role_id', 'competency_id'], 'rcr_role_competency_unique');
        });

        // ── 5. Recreate training_records with competency_id FK ──
        Schema::create('training_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignUuid('competency_id')->constrained('competencies');
            $table->unsignedTinyInteger('level_achieved')->default(0)
                  ->comment('0=belum, 1=lv1, 2=lv2, 3=lv3/certified');
            $table->date('completion_date')->nullable();
            $table->string('certification_number')->nullable();
            $table->date('certification_expiry')->nullable();
            $table->text('notes')->nullable();
            $table->enum('source', ['import', 'manual', 'system'])->default('import');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_records');
        Schema::dropIfExists('role_competency_requirements');
        Schema::dropIfExists('competency_modules');

        // Note: reversing the competency_tracks rename is complex; restore manually if needed
        Schema::table('competencies', function (Blueprint $table) {
            $table->dropColumn('tags');
            $table->enum('department', ['Sales', 'Aftersales', 'PD', 'HC', 'GS', 'Other'])->nullable();
            $table->string('category')->nullable();
            $table->unsignedTinyInteger('level_sequence')->nullable();
        });

        Schema::rename('competencies', 'competency_tracks');
    }
};
