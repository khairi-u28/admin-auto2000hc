<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('module_id')->constrained('modules')->cascadeOnDelete();
            $table->foreignUuid('course_id')->constrained('courses')->cascadeOnDelete();
            $table->tinyInteger('order_index')->unsigned()->default(0);
            $table->boolean('is_mandatory')->default(true);

            $table->unique(['module_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('module_courses');
    }
};
