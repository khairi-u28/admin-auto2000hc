<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create new batch management tables.
 * Parts 2.6 - 2.10 of Ruang Kompetensi data model changes.
 *
 * Creates: batches, batch_participants, batch_materi,
 *          participant_materi_progress, batch_feedback
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── batches ─────────────────────────────────────────────────────────
        Schema::create('batches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('batch_code')->unique();
            $table->string('name');
            $table->enum('type', ['HO', 'Cabang']);
            $table->foreignUuid('competency_id')->constrained('competencies');
            $table->foreignUuid('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->string('area_penyelenggara')->nullable();
            $table->foreignId('pic_id')->constrained('users');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedSmallInteger('target_participants')->default(30);
            $table->enum('status', ['draft', 'open', 'berlangsung', 'selesai', 'dibatalkan', 'pendaftaran', 'berjalan'])->default('draft');
            $table->longText('description')->nullable();
            $table->longText('evaluation')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // ── batch_participants ───────────────────────────────────────────────
        Schema::create('batch_participants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->foreignUuid('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('status', [
                'menunggu_undangan', 'diundang', 'terdaftar', 'sedang_berjalan',
                'selesai', 'terlambat', 'lulus', 'tidak_lulus', 'berlangsung',
            ])->default('menunggu_undangan');
            $table->text('participant_notes')->nullable();
            $table->timestamp('invitation_sent_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['batch_id', 'employee_id']);
        });

        // ── batch_materi ─────────────────────────────────────────────────────
        Schema::create('batch_materi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->foreignUuid('module_id')->constrained('modules');
            $table->foreignUuid('course_id')->constrained('courses');
            $table->unsignedTinyInteger('order_index')->default(0);
            $table->dateTime('session_datetime')->nullable();
            $table->string('session_link')->nullable();
            $table->string('session_venue')->nullable();
            $table->text('session_notes')->nullable();
            $table->timestamps();
        });

        // ── participant_materi_progress ──────────────────────────────────────
        Schema::create('participant_materi_progress', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->foreignUuid('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignUuid('course_id')->constrained('courses');
            $table->boolean('is_completed')->default(false);
            $table->integer('quiz_score')->nullable();
            $table->boolean('quiz_passed')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['batch_id', 'employee_id', 'course_id'], 'pmp_batch_emp_course_unique');
        });

        // ── batch_feedback ───────────────────────────────────────────────────
        Schema::create('batch_feedback', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->foreignUuid('employee_id')->constrained('employees')->cascadeOnDelete();
            // Rating Training
            $table->unsignedTinyInteger('training_relevance')->nullable();
            $table->unsignedTinyInteger('training_material_quality')->nullable();
            $table->unsignedTinyInteger('training_schedule')->nullable();
            $table->unsignedTinyInteger('training_facility')->nullable();
            $table->text('training_comments')->nullable();
            // Rating Trainer
            $table->unsignedTinyInteger('trainer_mastery')->nullable();
            $table->unsignedTinyInteger('trainer_delivery')->nullable();
            $table->unsignedTinyInteger('trainer_responsiveness')->nullable();
            $table->unsignedTinyInteger('trainer_attitude')->nullable();
            $table->text('trainer_comments')->nullable();
            $table->boolean('is_submitted')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            $table->unique(['batch_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_feedback');
        Schema::dropIfExists('participant_materi_progress');
        Schema::dropIfExists('batch_materi');
        Schema::dropIfExists('batch_participants');
        Schema::dropIfExists('batches');
    }
};
