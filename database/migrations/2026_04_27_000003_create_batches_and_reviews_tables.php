<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_batch')->unique();
            $table->string('nama_batch');
            $table->foreignUuid('curriculum_id')->nullable()->constrained('curricula')->nullOnDelete();
            $table->foreignUuid('pic_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->enum('status', ['aktif', 'non_aktif', 'selesai', 'dibatalkan'])->default('non_aktif');
            $table->date('active_from')->nullable();
            $table->date('active_until')->nullable();
            $table->timestamps();
        });

        Schema::create('batch_reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->uuidMorphs('reviewable');
            $table->unsignedTinyInteger('rating');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('batch_employee', function (Blueprint $table) {
            $table->foreignUuid('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->foreignUuid('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('role', ['peserta', 'trainer']);
            $table->timestamps();

            $table->primary(['batch_id', 'employee_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batch_employee');
        Schema::dropIfExists('batch_reviews');
        Schema::dropIfExists('batches');
    }
};
