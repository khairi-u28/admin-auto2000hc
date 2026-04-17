<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->enum('department', ['Sales', 'Aftersales', 'PD', 'HC', 'GS', 'Other']);
            $table->enum('type', ['video', 'pdf', 'article', 'quiz', 'offline_session', 'online_session']);
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->unsigned()->nullable();
            $table->string('file_path')->nullable();
            $table->string('external_url')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
