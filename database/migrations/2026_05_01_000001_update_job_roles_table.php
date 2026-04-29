<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Update job_roles — remove category, add golongan enum.
 * Part 2.1 of Ruang Kompetensi data model changes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_roles', function (Blueprint $table) {
            // Remove category column
            $table->dropColumn('category');
            // Add golongan enum (nullable to handle existing rows)
            $table->enum('golongan', ['I', 'II', 'III', 'IV', 'V'])->nullable()->after('level');
        });
    }

    public function down(): void
    {
        Schema::table('job_roles', function (Blueprint $table) {
            $table->dropColumn('golongan');
            $table->string('category')->nullable();
        });
    }
};
